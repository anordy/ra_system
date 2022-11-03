<?php

namespace App\Http\Livewire\Approval;

use App\Events\SendSms;
use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\TaxType;
use Livewire\Component;
use App\Events\SendMail;
use App\Enum\TaxAuditStatus;
use Livewire\WithFileUploads;
use App\Services\ZanMalipo\ZmCore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Returns\ReturnStatus;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessDeregistration;
use App\Models\Investigation\TaxInvestigation;
use App\Services\ZanMalipo\ZmResponse;
use Illuminate\Validation\Rules\NotIn;
use App\Models\TaxAudit\TaxAuditOfficer;
use App\Traits\WorkflowProcesssingTrait;
use App\Notifications\DatabaseNotification;
use Illuminate\Validation\Rules\RequiredIf;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxAudit\TaxAudit;
use Jantinnerezo\LivewireAlert\LivewireAlert;

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

            if ($this->preliminaryReport != $this->subject->preliminary_report) {
                $this->validate([
                    'preliminaryReport' => 'required|mimes:pdf|max:1024'
                ]);
            }

            if ($this->workingReport != $this->subject->working_report) {
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

            if ($this->exitMinutes != $this->subject->exit_minutes) {
                $this->validate([
                    'exitMinutes' => 'required|mimes:pdf|max:1024'
                ]);
            }

            if ($this->finalReport != $this->subject->final_report) {
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


                $taxpayer = $this->subject->business->taxpayer;
                event(new SendMail('audit-notification-to-taxpayer', $taxpayer));
                event(new SendSms('audit-notification-to-taxpayer', $taxpayer));

                $operators = [intval($this->teamLeader), intval($this->teamMember)];
            }


            if ($this->checkTransition('conduct_audit')) {

                $preliminaryReport = $this->preliminaryReport;
                if ($this->preliminaryReport != $this->subject->preliminary_report) {
                    $preliminaryReport = $this->preliminaryReport->store('audit', 'local-admin');
                }

                $workingReport = $this->workingReport;
                if ($this->workingReport != $this->subject->working_report) {
                    $workingReport = $this->workingReport->store('audit', 'local-admin');
                }

                $this->subject->preliminary_report = $preliminaryReport;
                $this->subject->working_report = $workingReport;
                $this->subject->save();
            }

            if ($this->checkTransition('preliminary_report_review')) {
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
                            'outstanding_amount' => $this->penaltyAmount + $this->interestAmount + $this->principalAmount,
                            'original_principal_amount' => $this->principalAmount,
                            'original_interest_amount' => $this->interestAmount,
                            'original_penalty_amount' => $this->penaltyAmount,
                            'original_total_amount' => $this->principalAmount + $this->interestAmount + $this->penaltyAmount
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
                            'outstanding_amount' => $this->penaltyAmount + $this->interestAmount + $this->principalAmount,
                            'original_principal_amount' => $this->principalAmount,
                            'original_interest_amount' => $this->interestAmount,
                            'original_penalty_amount' => $this->penaltyAmount,
                            'original_total_amount' => $this->principalAmount + $this->interestAmount + $this->penaltyAmount
                        ]);
                    }
                } else {
                    if ($assessment) {
                        $this->subject->assessment()->delete();
                    }
                }

                $exitMinutes = $this->exitMinutes;
                if ($this->exitMinutes != $this->subject->exit_minutes) {
                    $exitMinutes = $this->exitMinutes->store('audit', 'local-admin');
                }

                $finalReport = $this->finalReport;
                if ($this->finalReport != $this->subject->final_report) {
                    $finalReport = $this->finalReport->store('audit', 'local-admin');
                }

                $this->subject->exit_minutes = $exitMinutes;
                $this->subject->final_report = $finalReport;
                $this->subject->save();
            }

            //Send Exit Minute and Preliminary reports
            if ($this->subject->exit_minutes != null && $this->subject->preliminary_report != null) {
                event(new SendMail('send-report-to-taxpayer', [$this->subject->business->taxpayer, $this->subject]));
            }
            
            if ($this->checkTransition('accepted')) {
                // Notify audit manager to continue with business/location de-registration request if exists
                $deregister = BusinessDeregistration::where('tax_audit_id', $this->subject->id)->get()->first();

                if ($deregister) {
                    $auditManagerRole = Role::where('name', 'Audit Manager')->get()->first();
                    $auditManager = User::where('role_id', $auditManagerRole->id)->get()->first();

                    if ($auditManager) {
                        $auditManager->notify(new DatabaseNotification(
                            $subject = "{$deregister->business->name} audit has been completed",
                            $message = "{$deregister->business->name} audit for deregistration has been completed",
                            $href = 'business.viewDeregistration',
                            $hrefText = 'View',
                            $hrefParameters = $deregister->id
                        ));
                    }
                }
            }


            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments, 'operators' => $operators]);
            DB::commit();

            if ($this->subject->status == TaxAuditStatus::APPROVED && $this->subject->assessment()->exists()) {
                $this->generateControlNumber();
                $this->subject->assessment->update([
                    'payment_due_date' => Carbon::now()->addDays(30)->toDateTimeString(),
                    'curr_payment_due_date' => Carbon::now()->addDays(30)->toDateTimeString(),
                ]);
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }

        if ($this->subject->status == TaxAuditStatus::APPROVED && $this->subject->assessment()->exists()) {
            $this->generateControlNumber();
            $this->subject->assessment->update([
                'payment_due_date' => Carbon::now()->addDays(30)->toDateTimeString(),
                'curr_payment_due_date' => Carbon::now()->addDays(30)->toDateTimeString(),
            ]);

            event(new SendMail('audit-approved-notification', $this->subject->business->taxpayer));

        } else {
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        }
    }

    public function generateControlNumber()
    {
        $assessment = $this->subject->assessment;
        $taxType = null;
        if ($this->subject->tax_type_id == 0) {
            if ($this->subject->assessment->assessment_type == TaxAudit::class ) {
                $taxType = $this->subject->assessment->assessment_type::find($this->subject->assessment->assessment_id)->taxAuditTaxTypeNames();
            } else if ($this->subject->assessment->assessment_type == TaxInvestigation::class ) {
                $taxType = $this->subject->assessment->assessment_type::find($this->subject->assessment->assessment_id)->taxInvestigationTaxTypeNames();
            }    
        } else {
            $taxType = $this->subject->taxType;
        }

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
            $description = "Auditing for {$taxType}";
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
                    $assessment->payment_status = ReturnStatus::CN_GENERATING;
                    $assessment->save();

                    $this->alert('success', 'A control number has been generated successful.');
                } else {

                    $assessment->payment_status = ReturnStatus::CN_GENERATION_FAILED;
                    $assessment->save();
                    $this->alert('error', 'Control number generation failed, try again later');
                }
            } else {
                // We are local
                $assessment->payment_status = ReturnStatus::CN_GENERATED;
                $assessment->save();

                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = 'pending';
                $zmBill->control_number = rand(2000070001000, 2000070009999);
                $zmBill->save();

                $this->alert('success', 'A control number for this verification has been generated successfully');
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
