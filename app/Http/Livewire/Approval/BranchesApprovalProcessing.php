<?php

namespace App\Http\Livewire\Approval;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\BusinessStatus;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BranchesApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $taxRegions;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->registerWorkflow($modelName, $modelId);
    }

    public function approve($transtion)
    {
        $this->validate(['comments' => 'required']);

        if ($this->checkTransition('director_of_trai_review')) {
            $this->subject->verified_at = Carbon::now()->toDateTimeString();
            $this->subject->status = BusinessStatus::APPROVED;

            $notification_payload = [
                'branch' => $this->subject,
                'time' => Carbon::now()->format('d-m-Y')
            ];
            
            event(new SendSms('branch-approval', $notification_payload));
            event(new SendMail('branch-approval', $notification_payload));
        }

        try {
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            dd($e);
        }
        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
    }

    public function reject($transtion)
    {
        $notification_payload = [
            'branch' => $this->subject,
            'time' => Carbon::now()->format('d-m-Y')
        ];
        
        event(new SendSms('branch-correction', $notification_payload));
        event(new SendMail('branch-correction', $notification_payload));
        try {
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = BusinessStatus::CORRECTION;

                $notification_payload = [
                    'branch' => $this->subject,
                    'time' => Carbon::now()->format('d-m-Y')
                ];
                
                event(new SendSms('branch-correction', $notification_payload));
                event(new SendMail('branch-correction', $notification_payload));

            }
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            dd($e);
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }


    public function render()
    {
        return view('livewire.approval.branches');
    }
}
