<?php

namespace App\Http\Livewire\Business\Closure;

use Exception;
use Carbon\Carbon;
use App\Events\SendSms;
use Livewire\Component;
use App\Events\SendMail;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\BusinessStatus;
use App\Models\BusinessTempClosure;
use App\Traits\WorkflowProcesssingTrait;
use Illuminate\Support\Facades\Log;
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
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
    }


    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        try {
            if ($this->checkTransition('compliance_officer_review')) {

                if ($this->subject->closure_type == 'all') {
                    $business = Business::find($this->subject->business_id);

                    $business->update([
                        'status' => BusinessStatus::TEMP_CLOSED
                    ]);

                    // Close all locations
                    foreach ($business->locations as $location) {
                        $location->update([
                            'status' => BusinessStatus::TEMP_CLOSED
                        ]);
                    }

                } else {
                    // Close one location
                    $location = BusinessLocation::findOrFail($this->subject->location_id);

                    $location->update([
                        'status' => BusinessStatus::TEMP_CLOSED
                    ]);
                }

                $this->subject->status = BusinessStatus::APPROVED;
                $this->subject->approved_on = Carbon::now()->toDateTimeString();

                event(new SendSms('business-closure-approval', $this->subject));
                event(new SendMail('business-closure-approval', $this->subject));
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
        $this->validate(['comments' => 'required|strip_tag']);

        try {

            if ($this->checkTransition('compliance_officer_reject')) {

            }
            // if ($this->checkTransition('compliance_officer_reject')) {
            //     $this->subject->rejected_on = Carbon::now()->toDateTimeString();
            //     $this->subject->status = BusinessStatus::REJECTED;

            //     if ($this->subject->extended_from_id != null) {
            //         $previous_closure = BusinessTempClosure::findOrFail($this->subject->extended_from_id);
            //         $previous_closure->update([
            //             'show_extension' => true,
            //             'status' => 'approved'
            //         ]);
            //     }
            //     event(new SendSms('business-closure-rejected', $this->subject));
            //     event(new SendMail('business-closure-rejected', $this->subject));
            // } 
            
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->rejected_on = Carbon::now()->toDateTimeString();
                $this->subject->status = BusinessStatus::CORRECTION;
                event(new SendSms('business-closure-correction', $this->subject));
                event(new SendMail('business-closure-correction', $this->subject));
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact the administrator for help');
        }
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

    public function render()
    {
        return view('livewire.approval.closure');
    }
}
