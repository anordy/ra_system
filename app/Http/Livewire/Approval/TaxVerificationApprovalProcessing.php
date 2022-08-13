<?php

namespace App\Http\Livewire\Approval;

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
use App\Models\TaxAssessments\TaxAssessment;
use Illuminate\Support\Facades\Auth;
use App\Services\ZanMalipo\ZmResponse;
use Illuminate\Validation\Rules\NotIn;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Verification\TaxVerificationOfficer;
use Illuminate\Validation\Rules\RequiredIf;

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
    public $taxTypes;
    public $taxType;

    public $hasAssessment;

    public $staffs = [];
    public $subRoles = [];

    public $task;



    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = $modelId;
        $this->taxTypes = TaxType::all();
        $this->taxType = $this->taxTypes->firstWhere('code', TaxType::VERIFICATION);

        $this->registerWorkflow($modelName, $modelId);

        $this->task = $this->subject->pinstancesActive;
        $this->assessmentReport = $this->subject->assessment_report;

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


        if ($this->checkTransition('conduct_verification')) {
            $this->validate(
                [
                    'principalAmount' => [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'numeric'],
                    'interestAmount' => [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'numeric'],
                    'penaltyAmount' => [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'numeric'],
                    'assessmentReport' => 'required',
                ]
            );

            if ($this->assessmentReport != $this->subject->assessment_report) {
                $this->validate([
                    'assessmentReport' => 'required|mimes:pdf|max:1024'
                ]);
            }
        }

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
        }

        $operators = [];
        if ($this->checkTransition('assign_officers')) {

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

            $assessmentReport = $this->assessmentReport;
            if ($this->assessmentReport != $this->subject->assessment_report) {
                $assessmentReport = $this->assessmentReport->store('verification', 'local-admin');
            }

            $this->subject->assessment_report = $assessmentReport;
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


    public function generateControlNumber($verification_assessment)
    {
        $taxType = $this->subject->taxType;

        DB::beginTransaction();

        try {

            $billitems = [
                [
                    'billable_id' => $verification_assessment->id,
                    'billable_type' => get_class($verification_assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $this->principalAmount,
                    'currency' => 'TZS',
                    'gfs_code' => $this->taxTypes->where('code', 'verification')->first()->gfs_code,
                    'tax_type_id' => $this->taxTypes->where('code', 'verification')->first()->id
                ],
                [
                    'billable_id' => $verification_assessment->id,
                    'billable_type' => get_class($verification_assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $this->interestAmount,
                    'currency' => 'TZS',
                    'gfs_code' => $this->taxTypes->where('code', 'interest')->first()->gfs_code,
                    'tax_type_id' => $this->taxTypes->where('code', 'interest')->first()->id
                ],
                [
                    'billable_id' => $verification_assessment->id,
                    'billable_type' => get_class($verification_assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $this->penaltyAmount,
                    'currency' => 'TZS',
                    'gfs_code' => $this->taxTypes->where('code', 'penalty')->first()->gfs_code,
                    'tax_type_id' => $this->taxTypes->where('code', 'penalty')->first()->id
                ]
            ];

            $taxpayer = $this->subject->business->taxpayer;

            $payer_type = get_class($taxpayer);
            $payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
            $payer_email = $taxpayer->email;
            $payer_phone = $taxpayer->mobile;
            $description = "Debt for {$taxType->name} ";
            $payment_option = ZmCore::PAYMENT_OPTION_FULL;
            $currency = 'TZS';
            $createdby_type = get_class(Auth::user());
            $createdby_id = Auth::id();
            $exchange_rate = 0;
            $payer_id = $taxpayer->id;
            $expire_date = Carbon::now()->addMonth()->toDateTimeString();
            $billableId = $verification_assessment->id;
            $billableType = get_class($verification_assessment);

            $zmBill = ZmCore::createBill(
                $billableId,
                $billableType,
                $this->taxTypes->where('code', 'verification')->first()->id,
                $payer_id,
                $payer_type,
                $payer_name,
                $payer_email,
                $payer_phone,
                $expire_date,
                $description,
                $payment_option,
                $currency,
                $exchange_rate,
                $createdby_id,
                $createdby_type,
                $billitems
            );


            if (config('app.env') != 'local') {
                $response = ZmCore::sendBill($zmBill->id);
                if ($response->status === ZmResponse::SUCCESS) {
                    $verification_assessment->status = ReturnStatus::CN_GENERATING;
                    $verification_assessment->save();

                    $this->flash('success', 'A control number has been generated successful.');
                } else {

                    session()->flash('error', 'Control number generation failed, try again later');
                    $verification_assessment->status = ReturnStatus::CN_GENERATION_FAILED;
                }

                $verification_assessment->save();
            } else {
                // We are local
                $verification_assessment->status = ReturnStatus::CN_GENERATED;
                $verification_assessment->save();

                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = 'pending';
                $zmBill->control_number = '90909919991909';
                $zmBill->save();

                $this->flash('success', 'A control number for this verification has been generated successflu');
            }
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
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
        return view('livewire.approval.tax_verification');
    }
}
