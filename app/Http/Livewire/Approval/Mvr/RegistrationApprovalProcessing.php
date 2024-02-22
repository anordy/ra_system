<?php

namespace App\Http\Livewire\Approval\Mvr;

use App\Enum\BillStatus;
use App\Enum\MvrRegistrationStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistration;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class RegistrationApprovalProcessing extends Component
{
    use CustomAlert, WorkflowProcesssingTrait, PaymentsTrait;

    public $modelId;
    public $modelName;
    public $comments;

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

            if ($this->checkTransition('mvr_registration_officer_review')) {
                if ($this->subject->registrant_tin && !$this->subject->tin) {
                    $this->customAlert('warning', 'Please Verify Registrant TIN Number');
                    return;
                }
            }

            if ($this->checkTransition('mvr_registration_manager_review') && $transition === 'mvr_registration_manager_review') {
                $this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT;
                $this->subject->mvr_plate_number_status = MvrPlateNumberStatus::STATUS_NOT_ASSIGNED;
                $this->subject->save();

                $regType = $this->subject->regtype;

                if (!$regType->initial_plate_number) {
                    $this->customAlert('warning', 'Please make sure initial plate number for this registration type has been created');
                    return;
                }
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            // Send correction email/sms
            if ($this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT && $transition === 'mvr_registration_manager_review') {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "
                Hello {$this->subject->taxpayer->fullname}, your motor vehicle registration request for chassis number {$this->subject->chassis->chassis_number} has been approved, you will receive your payment control number shortly."]));
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->customAlert('error', 'Something went wrong');
            return;
        }

        // Generate Control Number after MVR RM Approval
        if ($this->subject->status == MvrRegistrationStatus::STATUS_PENDING_PAYMENT && $transition === 'mvr_registration_manager_review') {
            try {
                $this->generateControlNumber();
            } catch (Exception $exception) {
                $this->customAlert('error', 'Failed to generate control number, please try again');
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

            if ($this->checkTransition('mvr_registration_officer_review')) {
                $this->subject->status = MvrRegistrationStatus::CORRECTION;
                $this->subject->save();
            }

            if ($this->checkTransition('mvr_registration_manager_review')) {

            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status = MvrRegistrationStatus::CORRECTION) {
                // Send correction email/sms
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "
                Hello {$this->subject->taxpayer->fullname}, your motor vehicle registration request for chassis number {$this->subject->chassis->chassis_number} requires correction, please login to the system to perform data update."]));
            }

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
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

    public function generateControlNumber() {
        try {
            DB::beginTransaction();

            $this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT;
            $this->subject->payment_status = BillStatus::CN_GENERATING;

            //Generate control number
            $feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_REGISTRATION]);

            $fee = MvrFee::query()->where([
                'mvr_registration_type_id' => $this->subject->mvr_registration_type_id,
                'mvr_fee_type_id' => $feeType->id,
                'mvr_class_id' => $this->subject->mvr_class_id
            ])->first();

            if (empty($fee)) {
                $this->customAlert('error', "Registration fee for selected registration type is not configured");
                DB::rollBack();
                Log::error($fee);
                return;
            }

            $this->generateMvrControlNumber($this->subject, $fee);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Failed to generate control number, please try again');
        }
    }

    public function render()
    {
        return view('livewire.approval.mvr.registration');
    }
}
