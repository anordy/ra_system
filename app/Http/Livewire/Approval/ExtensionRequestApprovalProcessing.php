<?php

namespace App\Http\Livewire\Approval;

use App\Enum\ExtensionStatus;
use App\Enum\TaxClaimStatus;
use App\Models\Claims\TaxClaimAssessment;
use App\Models\Claims\TaxClaimOfficer;
use App\Models\Claims\TaxCredit;
use Carbon\Carbon;
use Exception;
use App\Models\Role;
use App\Models\User;
use App\Models\TaxType;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\NotIn;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ExtensionRequestApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert, WithFileUploads;

    public $modelId;
    public $modelName;
    public $comments;

    public $extendTo;
    public $taxTypes;

    public $staffs = [];
    public $subRoles = [];

    public $task;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = $modelId;

        $this->registerWorkflow($modelName, $modelId);

        $this->task = $this->subject->pinstancesActive;

        if ($this->task != null) {
            $operators = json_decode($this->task->operators);
            if (gettype($operators) != "array") {
                $operators = [];
            }
            $roles = Role::whereIn('id', $operators)->get()->pluck('id')->toArray();

            $this->subRoles = Role::whereIn('report_to', $roles)->get();

            $this->staffs = User::whereIn('role_id', $this->subRoles->pluck('id')->toArray())->get();
        }
    }

    public function approve($transtion)
    {
        $operators = [];
        if ($this->checkTransition('debt_manager')) {
            $this->validate([
                'extendTo' => ['required'],
            ]);

            $this->subject->extend_from = Carbon::now()->toDateTimeString();
            $this->subject->extend_to = $this->extendTo;
            $this->subject->save();
        }

        if ($this->checkTransition('accepted')) {
            $this->subject->status = ExtensionStatus::APPROVED;
            $this->subject->save();
        }

        try {
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments, 'operators' => $operators]);
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
        return view('livewire.approval.extension-request');
    }
}
