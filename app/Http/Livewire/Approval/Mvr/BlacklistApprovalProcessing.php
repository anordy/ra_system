<?php

namespace App\Http\Livewire\Approval\Mvr;

use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Enum\Mvr\MvrBlacklistInitiatorType;
use App\Enum\Mvr\MvrBlacklistType;
use App\Enum\MvrRegistrationStatus;
use App\Enum\MvrTemporaryTransportStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class BlacklistApprovalProcessing extends Component
{
    use CustomAlert, WorkflowProcesssingTrait;

    public $modelId;
    public $modelName;
    public $comments, $reason;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
        $this->reason = $this->subject->reasons;
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate(['comments' => 'required|strip_tag', 'reason' => 'required|strip_tag|max:255']);

        try {
            $filePath = null;

            if ($this->evidenceFile) {
                $filePath = $this->evidenceFile->store('mvr-blacklists', 'local');
                $this->subject->evidence_path = $filePath;
            }
            DB::beginTransaction();

            if ($transition === 'zartsa_officer_correct') {
                $this->subject->reasons = $this->reason;
            }

            if ($this->subject->initiator_type === MvrBlacklistInitiatorType::ZARTSA && $this->subject->type === MvrBlacklistType::DL) {
                if (!$this->subject->blacklist->is_blocked) {
                    $this->customAlert(GeneralConstant::WARNING, 'This driver license is not blocked');
                    return;
                }

                $this->subject->blacklist->is_blocked = !$this->blackListEntity->is_blocked;
                $this->subject->status = MvrRegistrationStatus::PENDING;

                if (!$this->subject->blacklist->save()) throw new \Exception('Unable to save blacklist');
            }

            if (!$this->subject->save()) throw new \Exception('Unable to save reason');
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            if ($filePath && Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
            }
            Log::error('BLACKLIST-APPROVAL-APPROVE', [$exception]);
            $this->customAlert(GeneralConstant::ERROR, CustomMessage::ERROR);
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
            $this->subject->status = MvrTemporaryTransportStatus::CORRECTION;
            $this->subject->save();
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('BLACKLIST-APPROVAL-REJECT', [$exception]);
            $this->customAlert(GeneralConstant::ERROR, CustomMessage::ERROR);
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
        return view('livewire.approval.mvr.blacklist-approval-processing');
    }
}
