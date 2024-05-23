<?php

namespace App\Http\Livewire\Approval\Mvr;

use App\Enum\BillStatus;
use App\Enum\MvrRegistrationStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\ChassisNumberChange;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrPlateNumberStatus;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ParticularApprovalProcessing extends Component
{
    use CustomAlert, WorkflowProcesssingTrait, PaymentsTrait,WithFileUploads;

    public $modelId;
    public $modelName;
    public $comments;
    public $approvalReport;
    public $engineNo, $chassisNo, $bodyStyle, $color;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);

        if ($this->subject->change) {
            $this->engineNo = $this->subject->change->engine_number;
            $this->color = $this->subject->change->color;
            $this->chassisNo = $this->subject->change->chassis_number;
            $this->bodyStyle = $this->subject->change->body_style;
        }
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        if ($this->checkTransition('mvr_zartsa_review')) {
            $this->validate([
                'engineNo' => 'nullable|alpha_num',
                'chassisNo' => 'nullable|alpha_num',
                'bodyStyle' => 'nullable|alpha',
                'color' => 'nullable|alpha',
                'approvalReport' => 'required|mimes:pdf|valid_pdf|max:1024|max_file_name_length:100',
            ]);
        }

        try {
            DB::beginTransaction();
            if ($this->checkTransition('mvr_zartsa_review')) {

                $approvalReport = "";
                if ($this->approvalReport) {
                    $approvalReport = $this->approvalReport->store('mvrZartsaReport', 'local');
                }
                $this->subject->approval_report = $approvalReport;
                $this->subject->save();

                ChassisNumberChange::updateOrCreate(
                    [
                        'particular_change_id' => $this->subject->id,
                    ],[
                    'color' => $this->color,
                    'engine_number' => $this->engineNo,
                    'chassis_number' => $this->chassisNo,
                    'body_style' => $this->bodyStyle,
                    'chassis_number_id' => $this->subject->chassis->id,
                    'particular_change_id' => $this->subject->id,
                ]);
            }

            if ($this->checkTransition('mvr_registration_manager_review') && $transition === 'mvr_registration_manager_review') {
                $this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT;
                $this->subject->payment_status = BillStatus::CN_GENERATING;
                $this->subject->mvr_plate_number_status = MvrPlateNumberStatus::STATUS_NOT_ASSIGNED;
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            // Send correction email/sms
            if ($this->subject->status == MvrRegistrationStatus::STATUS_PENDING_PAYMENT && $transition === 'mvr_registration_manager_review') {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "
                Hello {$this->subject->taxpayer->fullname}, your motor vehicle registration request for chassis number {$this->subject->chassis->chassis_number} has been approved, you will receive your payment control number shortly."]));
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            if (isset($approvalReport) && $approvalReport && Storage::exists($approvalReport)){
                Storage::delete($approvalReport);
            }
            Log::error('PARTICULAR-APPROVAL-APPROVE', [$exception]);
            $this->customAlert('error', 'Something went wrong');
            return;
        }

        // Generate Control Number after MVR SC Approval
        if ($this->subject->status == MvrRegistrationStatus::STATUS_PENDING_PAYMENT && $transition === 'mvr_registration_manager_review') {
            try {
                $feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_CHANGE_REGISTRATION]);

                $fee = MvrFee::query()->where([
                    'mvr_registration_type_id' => $this->subject->mvr_registration_type_id,
                    'mvr_fee_type_id' => $feeType->id,
                    'mvr_class_id' => $this->subject->mvr_class_id,
                    'mvr_plate_number_type_id' => $this->subject->mvr_plate_number_type_id
                ])->first();

                if (empty($fee)) {
                    $this->customAlert('error', "Particular change fee for selected registration type is not configured");
                    return;
                }

                $this->generateControlNumber($fee);
            } catch (Exception $exception) {
                Log::error('PARTICULAR-APPROVAL-CN-GENERATION', [$exception]);
                $this->customAlert('error', 'Failed to generate control number, please try again');
            }
        }

    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        try {
            DB::beginTransaction();

            if ($this->checkTransition('application_rejected')) {
                $this->subject->status = MvrRegistrationStatus::REJECTED;
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status = MvrRegistrationStatus::REJECTED) {
                // Send correction email/sms
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "
                Hello {$this->subject->taxpayer->fullname}, your motor vehicle particular change request for chassis number {$this->subject->chassis->chassis_number} has been rejected. Please re-submit new application."]));
            }

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('PARTICULAR-APPROVAL-REJECT', [$exception]);
            $this->customAlert('error', 'Something went wrong');
        }

    }

    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->customAlert('warning', 'Are you sure you want to complete this action?', [
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

    public function generateControlNumber($fee)
    {
        try {
            DB::beginTransaction();
            $this->generateMvrControlNumber($this->subject, $fee);
            DB::commit();
            $this->flash('success', 'Approved Successful', [], redirect()->back()->getTargetUrl());
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('PARTICULAR-APPROVAL-CN-GENERATION', [$exception]);
            $this->customAlert('error', 'Failed to generate control number, please try again');
        }
    }

    public function render()
    {
        return view('livewire.approval.mvr.particular-change');
    }
}
