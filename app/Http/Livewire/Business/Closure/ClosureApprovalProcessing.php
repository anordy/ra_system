<?php

namespace App\Http\Livewire\Business\Closure;

use App\Enum\BusinessDeRegTypeStatus;
use App\Enum\CustomMessage;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\BusinessStatus;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ClosureApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert;

    public $modelId;
    public $modelName;
    public $comments;

    public $officers = [];


    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);
    }


    public function approve($transition)
    {
        if (!isset($transition['data']['transition'])) {
            $this->customAlert('error', CustomMessage::ERROR);
            return;
        }
        $transition = $transition['data']['transition'];
        try {
            DB::beginTransaction();
            if ($this->checkTransition('compliance_officer_review')) {

                if ($this->subject->closure_type == BusinessDeRegTypeStatus::ALL) {
                    $business = Business::find($this->subject->business_id);

                    if (is_null($business)) {
                        abort(404);
                    }

                    $business->update([
                        'status' => BusinessStatus::TEMP_CLOSED
                    ]);

                    // Close all locations
                    $business->locations()->update([
                        'status' => BusinessStatus::TEMP_CLOSED
                    ]);


                } else {
                    // Close one location
                    $location = BusinessLocation::findOrFail($this->subject->location_id);

                    $location->update([
                        'status' => BusinessStatus::TEMP_CLOSED
                    ]);
                }

                $this->subject->status = BusinessStatus::APPROVED;
                $this->subject->approved_on = Carbon::now()->toDateTimeString();

            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();

            if ($this->subject->status === BusinessStatus::APPROVED) {
                event(new SendSms('business-closure-approval', $this->subject));
                event(new SendMail('business-closure-approval', $this->subject));
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('BUSINESS-CLOSURE-APPROVAL-PROCESSING', [$e->getMessage()]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function reject($transition)
    {
        if (!isset($transition['data']['transition'])) {
            $this->customAlert('error', CustomMessage::ERROR);
            return;
        }

        $transition = $transition['data']['transition'];
        $this->validate(['comments' => 'required|strip_tag']);

        try {
            DB::beginTransaction();
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->rejected_on = Carbon::now()->toDateTimeString();
                $this->subject->status = BusinessStatus::CORRECTION;
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();
            if ($this->subject->status = BusinessStatus::CORRECTION) {
                event(new SendSms('business-closure-correction', $this->subject));
                event(new SendMail('business-closure-correction', $this->subject));
            }
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('BUSINESS-CLOSURE-APPROVAL-PROCESSING', [$e->getMessage()]);
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
        return view('livewire.approval.closure');
    }
}
