<?php

namespace App\Http\Livewire\Approval;

use App\Models\Role;
use App\Models\User;
use App\Models\Verification\TaxVerificationAssessment;
use App\Models\Verification\TaxVerificationOfficer;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\NotIn;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class TaxVerificationApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert, WithFileUploads;
    public $modelId;
    public $modelName;
    public $comments;

    public $teamLeader;
    public $teamMember;

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
                    'teamLeader' => ['required',  new NotIn([$this->teamMember])],
                    'teamMember' => ['required',  new NotIn([$this->teamLeader])],
                ],
                [
                    'teamLeader.not_in' => 'Duplicate  already exists as team member',
                    'teamMember.not_in' => 'Duplicate already exists as team leader'
                ]
            );

            TaxVerificationOfficer::create([
                'verification_id' => $this->subject->id,
                'user_id' => $this->teamLeader,
                'team_leader' => true,
            ]);

            TaxVerificationOfficer::create([
                'verification_id' => $this->subject->id,
                'user_id' => $this->teamMember,
            ]);

            $operators = [$this->teamLeader, $this->teamMember];
        }

        if ($this->checkTransition('conduct_verification')) {
            $this->validate(
                [
                    'principalAmount' => ['required', 'numeric'],
                    'interestAmount' => ['required', 'numeric'],
                    'penaltyAmount' => ['required', 'numeric'],
                    'assessmentReport' => 'required|mimes:pdf',
                ]
            );

            $reportPath = "";
            if ($this->assessmentReport) {
                $reportPath = $this->assessmentReport->store('verification', 'local-admin');
            }


            TaxVerificationAssessment::create([
                'verification_id' => $this->subject->id,
                'principal_amount' => $this->principalAmount,
                'interest_amount' => $this->interestAmount,
                'penalty_amount' => $this->penaltyAmount,
                'report_path' => $reportPath ?? '',
            ]);
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
        return view('livewire.approval.tax_verification');
    }
}
