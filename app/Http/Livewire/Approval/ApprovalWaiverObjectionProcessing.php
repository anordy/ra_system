<?php

namespace App\Http\Livewire\Approval;

use App\Models\WaiverObjection;
use App\Models\WaiverObjectionStatus;
use App\Models\WaiverStatus;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApprovalWaiverObjectionProcessing extends Component
{
    use WorkflowProcesssingTrait,WithFileUploads, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $waiver_report;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->registerWorkflow($modelName, $modelId);

    }

    public function approve($transtion)
    {
        $this->validate([
            'comments' => 'required',
        ]);

        if ($this->checkTransition('objection_manager_review')) {

            // dd('waiver review');

        }

        if ($this->checkTransition('chief_assurance_reject')) {
            // dd('chief assuarance review');
        }

        if ($this->checkTransition('commisioner_review')) {
            // dd('chief assuarance review');
            $this->subject->verified_at = Carbon::now()->toDateTimeString();
            $this->subject->status = WaiverStatus::APPROVED;
            // event(new SendSms('business-registration-approved', $this->subject->id));
            // event(new SendMail('business-registration-approved', $this->subject->id));
        }

        try {
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);

            return;
        }
        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());

    }

    public function reject($transtion)
    {
        $this->validate([
            'comments' => 'required|string',
        ]);

        try {
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = WaiverObjectionStatus::CORRECTION;
                // event(new SendSms('business-registration-correction', $this->subject->id));
                // event(new SendMail('business-registration-correction', $this->subject->id));
            }
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);

            return;
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }

    public function render()
    {
        return view('livewire.approval.approval-waiver-objection-processing');
    }
}
