<?php

namespace App\Http\Livewire\Approval\PublicService;

use App\Enum\CustomMessage;
use App\Enum\PublicService\TemporaryClosureStatus;
use App\Enum\PublicServiceMotorStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TemporaryClosureApprovalProcessing extends Component
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
            Log::error('PUBLIC-SERVICE-TEMP-CLOSURE-APPROVAL-TRANSITION', ['Transition not set']);
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
                Log::error('PUBLIC-SERVICE-TEMP-CLOSURE-APPROVAL-OFFICER', [$exception]);
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

                $this->subject->status = TemporaryClosureStatus::APPROVED;
                $this->subject->approved_on = Carbon::now();
                $this->subject->save();

                if (Carbon::make($this->subject->closing_date)->lessThanOrEqualTo(Carbon::now()->toDateString()) &&
                    Carbon::make($this->subject->opening_date)->greaterThan(Carbon::now()->toDateString())){
                    $motor = $this->subject->motor;
                    $motor->status = PublicServiceMotorStatus::TEMP_CLOSED;
                    $motor->save();
                }

                DB::commit();

                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

                // Send approval email/sms
                if ($this->subject->status = TemporaryClosureStatus::APPROVED && $transition === 'public_service_registration_manager_review') {
                    event(new SendSms(SendCustomSMS::SERVICE, NULL, [
                        'phone' => $this->subject->motor->taxpayer->mobile,
                        'message' => "Hello {$this->subject->motor->taxpayer->fullname}, your request for public service temporary closure of {$this->subject->motor->mvr->plate_number} has been approved."
                    ]));
                }

                return $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());

            } catch (\Exception $exception) {
                DB::rollBack();
                Log::error('PUBLIC-SERVICE-TEMP-CLOSURE-APPROVAL-MANAGER', [$exception]);
                $this->customAlert('error', CustomMessage::ERROR);
                return;
            }
        }
    }

    public function reject($transition)
    {
        if (!isset($transition['data']['transition'])) {
            Log::error('PUBLIC-SERVICE-TEMP-CLOSURE-REJECT-TRANSITION', ['Transition not set']);
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
                    'message' => "Hello {$this->subject->motor->taxpayer->fullname}, your request for public service temporary closure of {$this->subject->motor->plate_number} has been rejected."
                ]));
            }

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('PUBLIC-SERVICE-TEMP-CLOSURE-APPROVAL-REJECT', [$exception]);
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
        return view('livewire.approval.public-service.temporary-closure');
    }

}
