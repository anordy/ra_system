<?php

namespace App\Http\Livewire\Approval\Mvr;

use App\Enum\BillStatus;
use App\Enum\MvrDeRegistrationReasonStatus;
use App\Enum\MvrRegistrationStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class DeRegistrationApprovalProcessing extends Component
{
    use CustomAlert, WorkflowProcesssingTrait, PaymentsTrait, WithFileUploads;

    public $modelId;
    public $modelName;
    public $comments;
    public $deregistration;
    public $reasonsForLost, $clearanceEvidence, $zicEvidence;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
        $this->deregistration = $this->subject;

        $this->clearanceEvidence = $this->subject->clearance_evidence;
        $this->zicEvidence = $this->subject->zic_evidence;
        $this->reasonsForLost = $this->subject->police_evidence;
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|strip_tag',
            'reasonsForLost' => $this->subject->reason->name === MvrDeRegistrationReasonStatus::LOST ? 'required|strip_tag' : 'nullable',
            'clearanceEvidence' => $this->subject->reason->name === MvrDeRegistrationReasonStatus::OUT_OF_ZANZIBAR ? ($this->clearanceEvidence === $this->subject->clearance_evidence ? 'nullable' : 'required|mimes:pdf|max:1024|max_file_name_length:100') : 'nullable',
            'zicEvidence' => $this->subject->reason->name === MvrDeRegistrationReasonStatus::SERVIER_ACCIDENT ? ($this->zicEvidence === $this->subject->zic_evidence ? 'nullable' : 'required|mimes:pdf|max:1024|max_file_name_length:100') : 'nullable'
        ]);

        try {
            DB::beginTransaction();

            if ($this->checkTransition('mvr_police_officer_review')) {

                // Update file/attachment based on type of de-registration reason
                if ($this->subject->reason->name === MvrDeRegistrationReasonStatus::LOST) {

                    $this->subject->police_evidence = $this->reasonsForLost;
                    $this->subject->clearance_evidence = null;
                    $this->subject->zic_evidence = null;

                } else if ($this->subject->reason->name === MvrDeRegistrationReasonStatus::OUT_OF_ZANZIBAR) {
                    $clearanceEvidence = $this->clearanceEvidence;

                    if ($this->clearanceEvidence != $this->subject->clearance_evidence) {
                        $clearanceEvidence = $this->clearanceEvidence->store('mvr_deregistration', 'local');
                    }

                    $this->subject->clearance_evidence = $clearanceEvidence;
                    $this->subject->police_evidence = null;
                    $this->subject->zic_evidence = null;

                } else if ($this->subject->reason->name === MvrDeRegistrationReasonStatus::SERVIER_ACCIDENT) {

                    $zicEvidence = $this->zicEvidence;

                    if ($this->zicEvidence != $this->subject->zic_evidence) {
                        $zicEvidence = $this->zicEvidence->store('mvr_deregistration', 'local');
                    }

                    $this->subject->zic_evidence = $zicEvidence;
                    $this->subject->clearance_evidence = null;
                    $this->subject->police_evidence = null;

                } else {
                    $this->customAlert('warning', 'Invalid Reason Provided');
                    return;
                }

            }


            if ($this->checkTransition('mvr_registration_manager_review') && $transition === 'mvr_registration_manager_review') {
                $this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT;
            }

            $this->subject->save();

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT && $transition === 'zbs_officer_review') {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "
                Hello {$this->subject->taxpayer->fullname}, your motor vehicle de-registration request for chassis number {$this->subject->chassis->chassis_number} has been approved, you will receive your payment control number shortly."]));
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->customAlert('error', 'Something went wrong');
            return;
        }

        // Generate Control Number after MVR DR Approval
        if ($this->subject->status == MvrRegistrationStatus::STATUS_PENDING_PAYMENT && $transition === 'zbs_officer_review') {
            try {
                $this->generateControlNumber();
            } catch (Exception $exception) {
                $this->flash('error', 'Failed to generate control number, please try again', [], redirect()->back()->getTargetUrl());
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

            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = MvrRegistrationStatus::CORRECTION;
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status = MvrRegistrationStatus::CORRECTION) {
                // Send correction email/sms
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "
                Hello {$this->subject->taxpayer->fullname}, your motor vehicle registration request for plate number {$this->subject->registration->plate_numer} requires correction, please login to the system to perform data update."]));
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

    public function generateControlNumber()
    {
        try {
            DB::beginTransaction();

            $this->subject->status = MvrRegistrationStatus::STATUS_PENDING_PAYMENT;
            $this->subject->payment_status = BillStatus::CN_GENERATING;

            //Generate control number
            $feeType = MvrFeeType::query()->firstOrCreate(['type' => MvrFeeType::TYPE_DE_REGISTRATION]);

            $fee = MvrFee::query()->where([
                'mvr_registration_type_id' => $this->subject->registration->mvr_registration_type_id,
                'mvr_fee_type_id' => $feeType->id,
                'mvr_class_id' => $this->subject->registration->mvr_class_id
            ])->first();

            if (empty($fee)) {
                $this->customAlert('error', "Registration fee for selected registration type is not configured");
                DB::rollBack();
                Log::error($fee);
                return;
            }

            $this->generateMvrControlNumber($this->subject, $fee);

            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Failed to generate control number, please try again');
        }
    }

    public function render()
    {
        return view('livewire.approval.mvr.de-registration');
    }
}
