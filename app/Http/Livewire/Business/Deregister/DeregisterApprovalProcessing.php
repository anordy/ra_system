<?php

namespace App\Http\Livewire\Business\Deregister;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\BusinessStatus;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class DeregisterApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments;


    public $officers = [];


    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->registerWorkflow($modelName, $modelId);
    }


    public function approve($transtion)
    {
        try {
            if ($this->checkTransition('commissioner_review')) {
                $this->subject->approved_on = Carbon::now()->toDateTimeString();
                $this->subject->status = BusinessStatus::APPROVED;
            }
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            dd($e);
        }
        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
    }

    public function reject($transtion)
    {
        try {
            if ($this->checkTransition('audit_manager_review')) {
                $this->subject->status = BusinessStatus::CORRECTION;
            } else if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->rejected_on = Carbon::now()->toDateTimeString();
                $this->subject->status = BusinessStatus::CORRECTION;
            }
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            dd($e);
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }


    public function render()
    {
        return view('livewire.approval.deregister');
    }
}
