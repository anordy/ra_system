<?php

namespace App\Http\Livewire\Approval\PublicService;

use App\Enum\CustomMessage;
use App\Enum\PublicService\DeRegistrationStatus;
use App\Enum\PublicServiceMotorStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DeRegistrationApprovalProcessing extends Component
{
    use CustomAlert, WorkflowProcesssingTrait;

    public $modelId;
    public $modelName;
    public $comments;

    protected $rules = [
        'psPaymentMonthId'  => 'nullable'
    ];

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
    }

    public function approve($transition)
    {
        if (!isset($transition['data']['transition'])) {
            Log::error('PUBLIC-SERVICE-DE-REG-APPROVAL-TRANSITION', ['Transition not set']);
            $this->customAlert('error', CustomMessage::ERROR);
            return;
        }

        $transition = $transition['data']['transition'];

        if ($this->checkTransition('public_service_registration_officer_review')) {
            $this->validate([
                'comments' => 'required|strip_tag',
            ]);

            try {
                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
                return $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
            } catch (\Exception $exception) {
                Log::error('PUBLIC-SERVICE-DE-REG-APPROVAL-OFFICER', [$exception]);
                $this->customAlert('error', CustomMessage::ERROR);
                return;
            }
        }

        if ($this->checkTransition('public_service_registration_manager_review')) {
            $this->validate([
                'comments' => 'required|strip_tag',
            ]);
            try {
                DB::beginTransaction();

                $this->subject->status = DeRegistrationStatus::APPROVED;
                $this->subject->approved_on = Carbon::now();
                $this->subject->save();

                $motor = $this->subject->motor;
                $motor->status = PublicServiceMotorStatus::DEREGISTERED;
                $motor->save();

                DB::commit();

                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

                // Send approval email/sms
                if ($this->subject->status = DeRegistrationStatus::APPROVED && $transition === 'public_service_registration_manager_review') {
                    event(new SendSms(SendCustomSMS::SERVICE, NULL, [
                        'phone' => $this->subject->motor->taxpayer->mobile,
                        'message' => "Hello {$this->subject->motor->taxpayer->fullname}, your request for public service de-registration of {$this->subject->motor->mvr->plate_number} has been approved."
                    ]));
                }

                return $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());

            } catch (\Exception $exception) {
                DB::rollBack();
                Log::error('APPROVAL-PUBLIC-SERVICE-DE-REG-APPROVE', [$exception]);
                $this->customAlert('error', CustomMessage::ERROR);
                return;
            }
        }
    }

    public function reject($transition)
    {
        if (!isset($transition['data']['transition'])) {
            Log::error('APPROVAL-PUBLIC-SERVICE-DE-REG-REJECT', ['Transition not set']);
            $this->customAlert('error', CustomMessage::ERROR);
            return;
        }

        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|strip_tag',
        ]);

        try {
            DB::beginTransaction();

            if ($this->checkTransition('public_service_registration_officer_review')) {
                $this->subject->status = PublicServiceMotorStatus::CORRECTION;
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            if ($this->subject->status = PublicServiceMotorStatus::CORRECTION) {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, [
                    'phone' => $this->subject->motor->taxpayer->mobile,
                    'message' => "Hello {$this->subject->motor->taxpayer->fullname}, your request for public service de-registration of {$this->subject->motor->plate_number} has been rejected."
                ]));
            }

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('APPROVAL-PUBLIC-SERVICE-DEREG-REJECT', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
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

    public function render()
    {
        return view('livewire.approval.public-service.de-registration');
    }

}
