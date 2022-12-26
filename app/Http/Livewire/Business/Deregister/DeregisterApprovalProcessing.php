<?php

namespace App\Http\Livewire\Business\Deregister;

use Exception;
use Carbon\Carbon;
use App\Events\SendSms;
use Livewire\Component;
use App\Events\SendMail;
use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\BusinessLocation;
use Illuminate\Support\Facades\Log;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class DeregisterApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comments;
    public $deregister;


    public $officers = [];


    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
        $this->deregister = $modelName::findOrFail($this->modelId);
    }

    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->alert('warning', 'Are you sure you want to complete this action?', [
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

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        try {
            if ($this->checkTransition('commissioner_review')) {

                if ($this->subject->deregistration_type == 'all') {
                    $business = Business::find($this->subject->business_id);

                    $business->update([
                        'status' => BusinessStatus::DEREGISTERED
                    ]);

                    // Deregister all locations
                    foreach ($business->locations as $location) {
                        $location->update([
                            'status' => BusinessStatus::DEREGISTERED
                        ]);
                    }

                } else {
                    // Deregister one location
                    $location = BusinessLocation::findOrFail($this->subject->location_id);

                    // Get new head quarter
                    if ($this->subject->new_headquarter_id != null) {
                        $selectedHeadQuarter = BusinessLocation::findOrFail($this->subject->new_headquarter_id);
                        $selectedHeadQuarter->update([
                            'is_headquarter' => 1
                        ]);
                    }

                    $location->update([
                        'status' => BusinessStatus::DEREGISTERED
                    ]);
                }

                $this->subject->status = BusinessStatus::APPROVED;
                $this->subject->approved_on = Carbon::now()->toDateTimeString();

                event(new SendSms('business-deregister-approval', $this->subject));
                event(new SendMail('business-deregister-approval', $this->subject));

            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate(['comments' => 'required']);
        try {
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = BusinessStatus::CORRECTION;

                event(new SendSms('business-deregister-correction', $this->subject));
                event(new SendMail('business-deregister-correction', $this->subject));
            } 
            
            if ($this->checkTransition('commissioner_reject_complete')) {
                $this->subject->rejected_on = Carbon::now()->toDateTimeString();
                $this->subject->status = BusinessStatus::REJECTED;

                event(new SendSms('business-deregister-rejected', $this->subject));
                event(new SendMail('business-deregister-rejected', $this->subject));
            } 
            
            if ($this->checkTransition('commissioner_reject')) {
                
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
    }


    public function render()
    {
        return view('livewire.approval.deregister');
    }
}
