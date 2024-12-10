<?php

namespace App\Http\Livewire\Approval\NonTaxResident;


use App\Enum\CustomMessage;
use App\Jobs\NonTaxResident\SendBusinessDeregistrationMail;
use App\Models\BusinessStatus;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class NtrBusinessDeregistrationApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert;

    public $modelId;
    public $modelName;
    public $comments;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);

        $this->registerWorkflow($modelName, $this->modelId);
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        try {

            DB::beginTransaction();

            if ($this->checkTransition('compliance_manager_review')) {
                $this->subject->status = BusinessStatus::APPROVED;
                $this->subject->business->status = BusinessStatus::DEREGISTERED;
                $this->subject->approved_on = Carbon::now();
                $this->subject->approved_by = Auth::id();
                if (!$this->subject->save()) throw new Exception('Failed to save de-registration data');
                if (!$this->subject->business->save()) throw new Exception('Failed to save de-registration business data');
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            // Send Mail
            if ($this->subject->business->email) {
                SendBusinessDeregistrationMail::dispatch($this->subject->business->email, $this->subject->business->name, BusinessStatus::APPROVED);
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('NON-TAX-RESIDENT-DE-REGISTRATION-APPROVE', [$e]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        try {
            DB::beginTransaction();
            if ($this->checkTransition('compliance_manager_reject') || $this->checkTransition('compliance_officer_reject')) {
                $this->subject->status = BusinessStatus::REJECTED;
                $this->subject->rejected_on = Carbon::now();
                $this->subject->rejected_by = Auth::id();
                if (!$this->subject->save()) throw new Exception('Failed to save de-registration data');
            }
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();
            if ($this->subject->business->email) {
                SendBusinessDeregistrationMail::dispatch($this->subject->business->email, $this->subject->business->name, BusinessStatus::REJECTED);
            }
            $this->flash('success', 'Application Rejected', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('NON-TAX-RESIDENT-DE-REGISTRATION-REJECT', [$e]);
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
        return view('livewire.approval.non-tax-resident.business-deregistration');
    }
}
