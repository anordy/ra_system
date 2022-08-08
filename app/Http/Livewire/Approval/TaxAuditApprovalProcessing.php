<?php

namespace App\Http\Livewire\Approval;

use App\Models\Role;
use App\Models\TaxAudit\TaxAuditAssessment;
use App\Models\TaxAudit\TaxAuditOfficer;
use App\Models\User;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\NotIn;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class TaxAuditApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert, WithFileUploads;
    public $modelId;
    public $modelName;
    public $comments;

    public $teamLeader;
    public $teamMember;
    public $auditingDate;
    public $preliminaryReport;
    public $workingReport;
    public $finalReport;
    public $exitMinutes;

    public $principalAmount;
    public $interestAmount;
    public $penaltyAmount;
    public $assessmentReport;

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
        if ($this->checkTransition('assign_officers')) {
            $this->validate(
                [
                    'auditingDate' => 'required|date',
                    'teamLeader' => ['required',  new NotIn([$this->teamMember])],
                    'teamMember' => ['required',  new NotIn([$this->teamLeader])],
                ],
                [
                    'teamLeader.not_in' => 'Duplicate  already exists as team member',
                    'teamMember.not_in' => 'Duplicate already exists as team leader'
                ]
            );

            $this->subject->auditing_date = $this->auditingDate;
            $this->subject->save();

            TaxAuditOfficer::create([
                'audit_id' => $this->subject->id,
                'user_id' => $this->teamLeader,
                'team_leader' => true,
            ]);

            TaxAuditOfficer::create([
                'audit_id' => $this->subject->id,
                'user_id' => $this->teamMember,
            ]);

            $operators = [$this->teamLeader, $this->teamMember];
        }


        if ($this->checkTransition('conduct_audit')) {
            $this->validate(
                [
                    'preliminaryReport' => 'required|mimes:pdf',
                    'workingReport' => 'required|mimes:pdf',
                ]
            );

            $preliminaryReport = "";
            if ($this->preliminaryReport) {
                $preliminaryReport = $this->preliminaryReport->store('audit', 'local-admin');
            }
            $workingReport = "";
            if ($this->workingReport) {
                $workingReport = $this->workingReport->store('audit', 'local-admin');
            }

            $this->subject->preliminary_report = $preliminaryReport;
            $this->subject->working_report = $workingReport;
            $this->subject->save();
        }

        if ($this->checkTransition('prepare_final_report')) {
            $this->validate(
                [
                    'principalAmount' => ['required', 'numeric'],
                    'interestAmount' => ['required', 'numeric'],
                    'penaltyAmount' => ['required', 'numeric'],
                    'finalReport' => 'required|mimes:pdf',
                    'exitMinutes' => 'required|mimes:pdf',
                ]
            );

            TaxAuditAssessment::create([
                'audit_id' => $this->subject->id,
                'principal_amount' => $this->principalAmount,
                'interest_amount' => $this->interestAmount,
                'penalty_amount' => $this->penaltyAmount,
            ]);

            $exitMinutes = "";
            if ($this->exitMinutes) {
                $exitMinutes = $this->exitMinutes->store('audit', 'local-admin');
            }
            $finalReport = "";
            if ($this->finalReport) {
                $finalReport = $this->finalReport->store('audit', 'local-admin');
            }

            $this->subject->exit_minutes = $exitMinutes;
            $this->subject->final_report = $finalReport;
            $this->subject->save();
        }


        try {


            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments, 'operators' => $operators]);
        } catch (Exception $e) {
            Log::error($e);
            return;
        }
        $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
    }

    public function reject($transtion)
    {
        $this->validate([
            'comments' => 'required|string',
        ]);

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
        return view('livewire.approval.tax_audit');
    }
}
