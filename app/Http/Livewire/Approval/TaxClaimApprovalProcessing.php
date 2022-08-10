<?php

namespace App\Http\Livewire\Approval;

use App\Enum\TaxClaimStatus;
use App\Models\Claims\TaxClaimAssessment;
use App\Models\Claims\TaxClaimOfficer;
use App\Models\Claims\TaxCredit;
use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\TaxType;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\ZanMalipo\ZmCore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Returns\ReturnStatus;
use Illuminate\Support\Facades\Auth;
use App\Services\ZanMalipo\ZmResponse;
use Illuminate\Validation\Rules\NotIn;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Verification\TaxVerificationOfficer;
use App\Models\Verification\TaxVerificationAssessment;

class TaxClaimApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert, WithFileUploads;
    public $modelId;
    public $modelName;
    public $comments;

    public $teamLeader;
    public $teamMember;

    public $paymentType;
    public $installmentCount;
    public $assessmentReport;
    public $taxTypes;

    public $staffs = [];
    public $subRoles = [];

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
            $roles = Role::whereIn('id', $operators)->get()->pluck('id')->toArray();

            $this->subRoles = Role::whereIn('report_to', $roles)->get();

            $this->staffs = User::whereIn('role_id', $this->subRoles->pluck('id')->toArray())->get();
        }
    }



    public function approve($transtion)
    {
        $taxType = $this->subject->taxType;

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

            TaxClaimOfficer::create([
                'claim_id' => $this->subject->id,
                'user_id' => $this->teamLeader,
                'team_leader' => true,
            ]);

            TaxClaimOfficer::create([
                'claim_id' => $this->subject->id,
                'user_id' => $this->teamMember,
            ]);

            $operators = [$this->teamLeader, $this->teamMember];
        }

        if ($this->checkTransition('verification_results')) {
            $this->validate(
                [
                    'assessmentReport' => 'required|mimes:pdf',
                ]
            );

            DB::beginTransaction();

            try {
                $reportPath = $this->assessmentReport->store('tax-claims');

                $assessment = TaxClaimAssessment::create([
                    'claim_id' => $this->subject->id,
                    'report_path' => $reportPath,
                ]);

                DB::commit();
            } catch (Exception $e) {
                Log::error($e);
                DB::rollBack();
                $this->alert('error', 'Something went wrong');
            }
        }

        if ($this->checkTransition('method_of_payment')){
            $this->validate([
                'paymentType' => 'required',
                'installmentCount' => 'required_if:paymentType,installment'
            ]);

            TaxCredit::create([
                'business_id' => $this->subject->business_id,
                'location_id' => $this->subject->location_id,
                'tax_type_id' => $this->subject->tax_type_id,
                'claim_id' => $this->subject->id,
                'payment_method' => $this->paymentType,
                'amount' => $this->subject->amount,
                'currency' => $this->subject->currency,
                'installments_count' => $this->installmentCount,
                'status' => 'draft'
            ]);

            $this->subject->status = TaxClaimStatus::APPROVED;
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
        return view('livewire.approval.tax_claim');
    }
}
