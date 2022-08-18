<?php

namespace App\Http\Livewire\Approval;

use App\Enum\ExtensionStatus;
use Carbon\Carbon;
use Exception;
use App\Models\TaxType;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class InstallmentRequestApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert, WithFileUploads;

    public $modelId;
    public $modelName;
    public $comments;
    public $installmentPhases;

    public $task;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = $modelId;
        $this->taxTypes = TaxType::all();

        $this->registerWorkflow($modelName, $modelId);

        $this->task = $this->subject->pinstancesActive;

        if ($this->task != null) {
            $operators = json_decode($this->task->operators);
            if (gettype($operators) != "array") {
                $operators = [];
            }
        }
    }

    public function approve($transtion)
    {

        if ($this->checkTransition('debt_manager')) {
            $this->validate([
                'installmentPhases' => ['required', 'numeric', 'min:1', 'max:12'],
            ]);

            $this->subject->installment_from = Carbon::now()->toDateTimeString();
            $this->subject->installment_to = Carbon::now()->addMonths($this->installmentPhases);
            $this->subject->installment_count = $this->installmentPhases;
            $this->subject->save();
        }

        if ($this->checkTransition('accepted')) {
            $this->subject->status = ExtensionStatus::APPROVED;
            $this->subject->save();
        }

        try {
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments, 'operators' => []]);
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', $e->getMessage());
            return;
        }

        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
    }

    public function reject($transtion)
    {
        $this->validate([
            'comments' => 'required|string',
        ]);

        if ($this->checkTransition('rejected')) {
            $this->subject->status = ExtensionStatus::REJECTED;
            $this->subject->save();
        }

        try {
            $this->doTransition($transtion, ['status' => 'reject', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);
            return;
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }

    public function render()
    {
        return view('livewire.approval.installment-request  ');
    }
}
