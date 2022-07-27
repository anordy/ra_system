<?php

namespace App\Http\Livewire\Business\Closure;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Events\SendSms;
use Livewire\Component;
use App\Events\SendMail;
use App\Models\Business;
use App\Models\BusinessStatus;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ClosureApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $officer_id;


    public $officers = [];


    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->registerWorkflow($modelName, $modelId);
        // $this->officers = User::all()->where('role_id', 5);
    }


    public function approve($transtion)
    {
        try {
            if ($this->checkTransition('compliance_officer_review')) {
                $this->subject->approved_on = Carbon::now()->toDateTimeString();
                $this->subject->status = BusinessStatus::APPROVED;
                $business = Business::find($this->subject->business_id);
                $business->update([
                    'status' => BusinessStatus::TEMP_CLOSED
                ]);
                event(new SendSms('business-closure-approval', $this->subject->business_id));
                event(new SendMail('business-closure-approval', $this->subject->business_id));
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
            if ($this->checkTransition('compliance_officer_reject')) {
                $this->subject->rejected_on = Carbon::now()->toDateTimeString();
                $this->subject->status = BusinessStatus::REJECTED;
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
        return view('livewire.approval.closure');
    }
}
