<?php

namespace App\Http\Livewire\Approval;

use App\Enum\TaxAuditStatus;
use App\Models\Returns\ReturnStatus;
use App\Models\Role;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxAudit\TaxAuditOfficer;
use App\Models\TaxType;
use App\Models\User;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
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
        $this->workingReport = $this->subject->working_report;
        $this->preliminaryReport = $this->subject->preliminary_report;

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
                    'periodFrom' => 'required|date',
                    'periodTo' => 'required|after:periodFrom',
                    'auditingDate' => 'required|after:periodTo',
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
                    'preliminaryReport' => 'required',
                    'workingReport' => 'required',
                ]
            );

            if($this->preliminaryReport != $this->subject->preliminary_report){
                $this->validate([
                    'preliminaryReport' => 'required|mimes:pdf|max:1024'
                ]);
            }

            if($this->workingReport != $this->subject->working_report){
                $this->validate([
                    'workingReport' => 'required|mimes:pdf|max:1024'
                ]);
            }
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

                $officers = $this->subject->officers()->exists();

                if ($officers) {
                    $this->subject->officers()->delete();
                }


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
                
                $preliminaryReport = $this->preliminaryReport;
                if($this->preliminaryReport != $this->subject->preliminary_report){
                    $preliminaryReport = $this->preliminaryReport->store('audit', 'local-admin');
                }
    
                $workingReport = $this->workingReport;
                if($this->workingReport != $this->subject->working_report){
                    $workingReport = $this->workingReport->store('audit', 'local-admin');
                }

                $this->subject->preliminary_report = $preliminaryReport;
                $this->subject->working_report = $workingReport;
                $this->subject->save();
            }

            if($this->checkTransition('preliminary_report_review')){
                $operators = $this->subject->officers->pluck('user_id')->toArray();
            }

            if ($this->checkTransition('prepare_final_report')) {
                $assessment = $this->subject->assessment()->exists();

                if ($this->hasAssessment == "1") {
                    if ($assessment) {
                        $this->subject->assessment()->update([
                            'principal_amount' => $this->principalAmount,
                            'interest_amount' => $this->interestAmount,
                            'penalty_amount' => $this->penaltyAmount,
                            'total_amount' => $this->penaltyAmount + $this->interestAmount + $this->principalAmount,
                        ]);
                    } else {
                        TaxAssessment::create([
                            'location_id' => $this->subject->location_id,
                            'business_id' => $this->subject->business_id,
                            'tax_type_id' => $this->taxType->id,
                            'assessment_id' => $this->subject->id,
                            'assessment_type' => get_class($this->subject),
                            'principal_amount' => $this->principalAmount,
                            'interest_amount' => $this->interestAmount,
                            'penalty_amount' => $this->penaltyAmount,
                            'total_amount' => $this->penaltyAmount + $this->interestAmount + $this->principalAmount,
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

        if ($this->subject->status == TaxAuditStatus::APPROVED && $this->subject->assessment()->exists()) {
            $this->generateControlNumber();
            $this->subject->assessment->update([
                'payment_due_date' => Carbon::now()->addDays(30)->toDateTimeString(),
            ]);
        } else {
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        }
    }

    public function generateControlNumber()
    {
        $assessment = $this->subject->assessment;
        $taxType = $this->subject->taxType;

        DB::beginTransaction();

        try {
            $billitems = [
                [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $this->principalAmount,
                    'currency' => 'TZS',
                    'gfs_code' => $this->taxTypes->where('code', 'audit')->first()->gfs_code,
                    'tax_type_id' => $this->taxTypes->where('code', 'audit')->first()->id
                ],
                [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $this->interestAmount,
                    'currency' => 'TZS',
                    'gfs_code' => $this->taxTypes->where('code', 'interest')->first()->gfs_code,
                    'tax_type_id' => $this->taxTypes->where('code', 'interest')->first()->id
                ],
                [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
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
            $description = "Verification for {$taxType->name} ";
            $payment_option = ZmCore::PAYMENT_OPTION_FULL;
            $currency = 'TZS';
            $createdby_type = get_class(Auth::user());
            $createdby_id = Auth::id();
            $exchange_rate = 1;
            $payer_id = $taxpayer->id;
            $expire_date = Carbon::now()->addDays(30)->toDateTimeString();
            $billableId = $assessment->id;
            $billableType = get_class($assessment);

            $zmBill = ZmCore::createBill(
                $billableId,
                $billableType,
                $this->taxTypes->where('code', 'audit')->first()->id,
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
                    $assessment->status = ReturnStatus::CN_GENERATING;
                    $assessment->save();

                    $this->flash('success', 'A control number has been generated successful.');
                } else {

                    session()->flash('error', 'Control number generation failed, try again later');
                    $assessment->status = ReturnStatus::CN_GENERATION_FAILED;
                }

                $assessment->save();
            } else {
                // We are local
                $assessment->status = ReturnStatus::CN_GENERATED;
                $assessment->save();

                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = 'pending';
                $zmBill->control_number = rand(2000070001000, 2000070009999);
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
            $operators = [];
            if ($this->checkTransition('correct_preliminary_report')) {
                $operators = $this->subject->officers->pluck('user_id')->toArray();
            }
            if ($this->checkTransition('correct_final_report')) {
                $operators = $this->subject->officers->pluck('user_id')->toArray();
            }

            $this->doTransition($transtion, ['status' => 'reject', 'comment' => $this->comments, 'operators' => $operators]);
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
