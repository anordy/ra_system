<?php

namespace App\Http\Livewire\Approval;

use App\Models\Role;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxAudit\TaxAuditOfficer;
use App\Models\TaxType;
use App\Models\User;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\NotIn;
use Illuminate\Validation\Rules\RequiredIf;
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
    public $periodTo;
    public $periodFrom;
    public $intension;
    public $scope;

    public $principalAmount;
    public $interestAmount;
    public $penaltyAmount;
    public $assessmentReport;

    public $hasAssessment;
    
    public $taxTypes;
    public $taxType;

    public $staffs = [];
    public $subRoles = [];

    public $task;



    public function mount($modelName, $modelId)
    {
        $this->taxTypes = TaxType::all();
        $this->taxType = $this->taxTypes->firstWhere('code', TaxType::AUDIT);

        
        $this->modelName = $modelName;
        $this->modelId   = $modelId;
        $this->registerWorkflow($modelName, $modelId);

        $this->task = $this->subject->pinstancesActive;
        $this->periodFrom = $this->subject->period_from;
        $this->periodTo = $this->subject->period_to;
        $this->intension = $this->subject->intension;
        $this->scope = $this->subject->scope;
        $this->auditingDate = $this->subject->auditing_date;
        $this->exitMinutes = $this->subject->exit_minutes;
        $this->finalReport = $this->subject->final_report;

        $assessment = $this->subject->assessment;
        if ($assessment) {
            $this->hasAssessment = "1";
            $this->principalAmount = $assessment->principal_amount;
            $this->interestAmount = $assessment->interest_amount;
            $this->penaltyAmount = $assessment->penalty_amount;
        } else {
            $this->hasAssessment = "0";
        }

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
        if ($this->checkTransition('assign_officers')) {
            $this->validate(
                [
                    'auditingDate' => 'required|date',
                    'periodFrom' => 'required|date',
                    'periodTo' => 'required|date',
                    'intension' => 'required',
                    'scope' => 'required',
                    'teamLeader' => ['required',  new NotIn([$this->teamMember])],
                    'teamMember' => ['required',  new NotIn([$this->teamLeader])],
                ],
                [
                    'teamLeader.not_in' => 'Duplicate  already exists as team member',
                    'teamMember.not_in' => 'Duplicate already exists as team leader'
                ]
            );
        }

        if ($this->checkTransition('conduct_audit')) {
            $this->validate(
                [
                    'preliminaryReport' => 'required|mimes:pdf',
                    'workingReport' => 'required|mimes:pdf',
                ]
            );
        }
        if ($this->checkTransition('prepare_final_report')) {
            $this->validate(
                [
                    'finalReport' => 'required',
                    'exitMinutes' => 'required',
                    'hasAssessment' => ['required', 'boolean'],
                    'principalAmount' => [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'numeric'],
                    'interestAmount' => [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'numeric'],
                    'penaltyAmount' => [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'numeric'],
                ]
            );

            if($this->exitMinutes != $this->subject->exit_minutes){
                $this->validate([
                    'exitMinutes' => 'required|mimes:pdf|max:1024'
                ]);
            }

            if($this->finalReport != $this->subject->final_report){
                $this->validate([
                    'finalReport' => 'required|mimes:pdf|max:1024'
                ]);
            }
        };

        DB::beginTransaction();
        try {

            $operators = [];
            if ($this->checkTransition('assign_officers')) {
    
                $this->subject->auditing_date = $this->auditingDate;
                $this->periodFrom = $this->subject->period_from;
                $this->periodTo = $this->subject->period_to;
                $this->intension = $this->subject->intension;
                $this->scope = $this->subject->scope;
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

                $assessment = $this->subject->assessment()->exists();

                if ($this->hasAssessment == "1") {
                    if ($assessment) {
                        $this->subject->assessment()->update([
                            'principal_amount' => $this->principalAmount,
                            'interest_amount' => $this->interestAmount,
                            'penalty_amount' => $this->penaltyAmount,
                        ]);
                    } else {
                        TaxAssessment::create([
                            'location_id' => $this->subject->location_id,
                            'business_id' => $this->stubject->business_id,
                            'tax_type_id' => $this->taxType->id,
                            'assessment_id' => $this->subject->id,
                            'assessment_type' => get_class($this->subject),
                            'principal_amount' => $this->principalAmount,
                            'interest_amount' => $this->interestAmount,
                            'penalty_amount' => $this->penaltyAmount,
                        ]);
                    }
                } else {
                    if ($assessment) {
                        $this->subject->assessment()->delete();
                    }
                }

                $exitMinutes = $this->exitMinutes;
                if($this->exitMinutes != $this->subject->exit_minutes){
                    $exitMinutes = $this->exitMinutes->store('audit', 'local-admin');
                    
                }
    
                $finalReport = $this->finalReport;
                if($this->finalReport != $this->subject->final_report){
                    $finalReport = $this->finalReport->store('audit', 'local-admin');
                }

                $this->subject->exit_minutes = $exitMinutes;
                $this->subject->final_report = $finalReport;
                $this->subject->save();
            }


            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments, 'operators' => $operators]);
            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function hasNoticeOfAttachmentChange($value)
    {
        if ($value != "1") {
            $this->principalAmount = null;
            $this->interestAmount = null;
            $this->penaltyAmount = null;
        }
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
