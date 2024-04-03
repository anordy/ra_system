<?php

namespace App\Http\Livewire\Approval\Mvr;

use App\Enum\BillStatus;
use App\Enum\CustomMessage;
use App\Enum\MvrRegistrationStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\MvrTransferFee;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class TransferApprovalProcessing extends Component
{
    use CustomAlert, WorkflowProcesssingTrait, PaymentsTrait,WithFileUploads;

    public $modelId;
    public $modelName;
    public $comments;
    public $approvalReport, $agreementContract, $vehicleInspectionReport;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
    }
    public function approve($transition) {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        try {
            DB::beginTransaction();
            if ($this->checkTransition('bpra_officer_review')) {

                $this->validate(
                    [
                        'agreementContract' => 'nullable|mimes:pdf|max:3072|max_file_name_length:100|valid_pdf',
                    ]
                );

                $agreementContract = "";
                if ($this->agreementContract) {
                    $agreementContract = $this->agreementContract->store('mvrZartsaReport', 'local');
                }
                $this->subject->agreement_contract_path = $agreementContract;
                $this->subject->save();
            }

            if ($this->checkTransition('zbs_officer_review')) {
                $this->validate(
                    [
                        'vehicleInspectionReport' => 'nullable|mimes:pdf|valid_pdf|max:3072|max_file_name_length:100',
                    ]
                );

                $inspectionReport = "";
                if ($this->vehicleInspectionReport) {
                    $inspectionReport = $this->vehicleInspectionReport->store('mvrZartsaReport', 'local');
                }
                $this->subject->inspection_report = $inspectionReport;
                $this->subject->save();
            }

            if ($this->checkTransition('mvr_registration_manager_review') && $transition === 'mvr_registration_manager_review') {
                $this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT;
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            // Send correction email/sms
            if ($this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT && $transition === 'mvr_registration_manager_review') {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->previous_owner->mobile, 'message' => "
                Hello {$this->subject->previous_owner->fullname}, your motor vehicle transfer request for chassis number {$this->subject->motor_vehicle->chassis->chassis_number} has been approved, you will receive your payment control number shortly."]));
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('MVR-TRANSFER-APPROVAL-APPROVE', [$exception]);
            $this->customAlert('error', 'Something went wrong');
            return;
        }

        // Generate Control Number after MVR SC Approval
        if ($this->subject->status == MvrRegistrationStatus::STATUS_PENDING_PAYMENT && $transition === 'mvr_registration_manager_review') {
            try {
                $this->generateControlNumber();
            } catch (Exception $exception) {
                $this->customAlert('error', 'Failed to generate control number, please try again');
                $this->flash('error', CustomMessage::ERROR, [], redirect()->back()->getTargetUrl());
            }
        }

    }

    public function reject($transition) {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        try {
            DB::beginTransaction();

            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = MvrRegistrationStatus::CORRECTION;
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status = MvrRegistrationStatus::CORRECTION) {
                // Send correction email/sms
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->previous_owner->mobile, 'message' => "
                Hello {$this->subject->previous_owner->fullname}, your transfer ownership request for chassis number {$this->subject->motor_vehicle->chassis->chassis_number} requires correction, please login to the system to perform data update."]));
            }

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('MVR-TRANSFER-APPROVAL-REJECT', [$exception]);
            $this->customAlert('error', 'Something went wrong');
        }

    }

    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->customAlert('warning', CustomMessage::ARE_YOU_SURE, [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => $action,
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'transition' => $transition
            ],

        ]);
    }

    public function generateControlNumber() {
        try {
            $fee = MvrTransferFee::query()->where([
                'mvr_transfer_category_id' => $this->subject->mvr_transfer_category_id,
            ])->first();

            DB::beginTransaction();
            $this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT;
            $this->subject->payment_status = BillStatus::CN_GENERATING;

            if (empty($fee)) {
                $this->customAlert('error', "Ownership Transfer fee is not configured");
                DB::rollBack();
                Log::error($fee);
                return;
            }

            $this->generateMvrControlNumber($this->subject->motor_vehicle, $fee);

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('MVR-TRANSFER-APPROVAL-CN-GENERATION', [$exception]);
            $this->customAlert('error', 'Failed to generate control number, please try again');
        }
    }

    public function render()
    {
        return view('livewire.approval.mvr.status');
    }
}
