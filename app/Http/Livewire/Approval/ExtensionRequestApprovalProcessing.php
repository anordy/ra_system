<?php

namespace App\Http\Livewire\Approval;

use App\Enum\ExtensionRequestStatus;
use App\Models\Debts\Debt;
use App\Models\Returns\TaxReturn;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ExtensionRequestApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert;

    public $modelId;
    public $modelName;
    public $comments;

    public $extendTo;
    public $taxTypes;

    public $task;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = $modelId;

        $this->registerWorkflow($modelName, $modelId);

        $this->task = $this->subject->pinstancesActive;
    }

    public function approve($transtion)
    {
        if ($this->checkTransition('debt_manager')) {
            $this->validate([
                'extendTo' => ['required'],
            ]);
        }

        DB::beginTransaction();

        try {
            if ($this->checkTransition('debt_manager')) {
                $this->subject->extend_from = $this->subject->taxReturn->curr_payment_due_date;
                $this->subject->extend_to = $this->extendTo;
                $this->subject->save();
            }

            if ($this->checkTransition('accepted')) {
                $this->subject->status = ExtensionRequestStatus::APPROVED;
                $taxReturn = TaxReturn::findOrFail($this->subject->tax_return_id);
                $taxReturn->update([
                    'curr_payment_due_date' => $this->subject->extend_to
                ]);
                // TODO: Log this change
                $this->subject->save();
            }

            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, please try again later.');
            return;
        }
    }

    public function reject($transtion)
    {
        $this->validate([
            'comments' => 'required|string',
        ]);
        DB::beginTransaction();

        try {
            if ($this->checkTransition('rejected')) {
                $this->subject->status = ExtensionRequestStatus::REJECTED;
                $this->subject->save();
            }

            $this->doTransition($transtion, ['status' => 'reject', 'comment' => $this->comments]);
            DB::commit();
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, please try again later.');
            return;
        }
    }

    public function render()
    {
        return view('livewire.approval.extension-request');
    }
}
