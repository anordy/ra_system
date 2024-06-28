<?php

namespace App\Http\Livewire\Approval\Mvr;

use App\Enum\MvrTemporaryTransportStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Traits\CustomAlert;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class TemporaryTransportApprovalProcessing extends Component
{
    use CustomAlert, WorkflowProcesssingTrait, PaymentsTrait, WithFileUploads;

    public $modelId;
    public $modelName;
    public $comments;
    public $transport;
    public $description;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
        $this->transport = $this->subject;
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate(['comments' => 'required|strip_tag']);

        try {
            DB::beginTransaction();

            if ($this->checkTransition('mvr_registration_manager_review') && $transition === 'mvr_registration_manager_review') {
                $this->subject->status = MvrTemporaryTransportStatus::APPROVED;
                $this->subject->approved_on = Carbon::now();
                $this->subject->save();
            }

            $this->subject->save();
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();

            if ($this->checkTransition('mvr_registration_manager_review') && $transition === 'mvr_registration_manager_review') {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "
                Hello {$this->subject->taxpayer->fullname}, your motor vehicle temporary transportation request for {$this->subject->mvr->plate_number} has been approved."]));
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('TEMPORARY-TRANSPORT-APPROVE', [$exception]);
            $this->customAlert('error', 'Something went wrong');
            return;
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

            $this->subject->status = MvrTemporaryTransportStatus::REJECTED;
            $this->subject->save();
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();

            if ($this->subject->status == MvrTemporaryTransportStatus::CORRECTION) {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $this->subject->taxpayer->mobile, 'message' => "
                Hello {$this->subject->taxpayer->fullname}, your motor vehicle temporary transportation request for {$this->subject->mvr->plate_number} was rejected."]));
            }

            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('TEMPORARY-TRANSPORT-REJECT', [$exception]);
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

    public function render()
    {
        return view('livewire.approval.mvr.temporary-transport');
    }
}

