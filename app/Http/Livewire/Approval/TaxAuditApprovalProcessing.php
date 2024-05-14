<?php

namespace App\Http\Livewire\Approval;

use App\Enum\TaxAuditStatus;
use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\BusinessDeregistration;
use App\Models\Investigation\TaxInvestigation;
use App\Models\Region;
use App\Models\Returns\ReturnStatus;
use App\Models\Role;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxAudit\TaxAudit;
use App\Models\TaxAudit\TaxAuditOfficer;
use App\Models\TaxType;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\PaymentsTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\NotIn;
use Illuminate\Validation\Rules\RequiredIf;
use App\Traits\CustomAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class TaxAuditApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, WithFileUploads, PaymentsTrait;
    public $modelId;
    public $modelName;
    public $comments;

    public $teamLeader;
    public $teamMember;
    public $auditingDate;
    public $notificationLetter;
    public $preliminaryReport;
    public $workingReport;
    public $audit;
    public $entryMeeting;
    public $finalReport;
    public $exitMinutes;
    public $auditDocuments = [];
    public $periodTo;
    public $periodFrom;
    public $intension;
    public $scope;

    public $principalAmount;
    public $interestAmount;
    public $penaltyAmount;
    public $assessmentReport;

    public $hasAssessment;
    public $forwardToCG = false;

    public $taxTypes;
    public $taxType;
    public $taxAssessments = [];
    public $grandTotal;

    public $staffs = [];
    public $subRoles = [];

    public $principalAmounts = [];
    public $interestAmounts = [];
    public $penaltyAmounts = [];
    public $taxTypeIds = [];
    public $task;



    public function mount($modelName, $modelId)
    {
        $this->taxTypes = TaxType::all();
        $this->taxType = $this->taxTypes->firstWhere('code', TaxType::AUDIT);

        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->registerWorkflow($modelName, $this->modelId);

        $assessment = $this->subject->assessment;
        if ($assessment) {
            $this->hasAssessment = "1";

            $this->taxAssessments = TaxAssessment::where('assessment_id', $this->subject->id)
                ->where('assessment_type', get_class($this->subject))
                ->get();
        } else {
            $this->hasAssessment = "0";
        }


        $this->task = $this->subject->pinstancesActive;
        if (!isNullOrEmpty($this->subject->period_from)) {
            $this->periodFrom = Carbon::create($this->subject->period_from)->format('Y-m-d');
        }
        if (!isNullOrEmpty($this->subject->period_to)) {
            $this->periodTo = Carbon::create($this->subject->period_to)->format('Y-m-d');
        }
        $this->intension = $this->subject->intension;
        $this->scope = $this->subject->scope;
        if (!isNullOrEmpty($this->subject->auditing_date)) {
            $this->auditingDate = Carbon::create($this->subject->auditing_date)->format('Y-m-d');
        }

        if ($this->checkTransition('audit_team_review')) {
            $this->auditDocuments = DB::table('tax_audit_files')->where('tax_audit_id', $this->modelId)->get();
            $this->auditDocuments = json_decode($this->auditDocuments, true);

        }

        if ($this->checkTransition('prepare_final_report')) {
            $this->audit = TaxAudit::find($this->modelId);

            // Initialize properties with empty arrays for each tax type
            $taxTypes = $this->audit->taxAuditTaxType();

            foreach ($taxTypes as $taxType) {
                // Replace spaces with underscores (_) in tax type names
                $taxTypeKey = str_replace(' ', '_', $taxType['name']);
                $this->principalAmounts[$taxTypeKey] = null;
                $this->interestAmounts[$taxTypeKey] = null;
                $this->penaltyAmounts[$taxTypeKey] = null;
                $this->taxTypeIds[$taxTypeKey] = $taxType['id'];
            }
        }

        if ($this->checkTransition('final_report_review')) {
            $taxRegion = $this->subject->location->taxRegion->location;

            // Initialize grand total
            $grandTotal = 0;

            // Calculate grand total
            foreach ($this->taxAssessments as $taxAssessment) {
                $grandTotal += $taxAssessment->total_amount ?? 0;
            }
            // Check if tax liability exceeds the threshold for forwarding to Commissioner General
            if ($taxRegion == Region::LTD && $grandTotal > 500000000) {
                $this->forwardToCG = true;
            } elseif ($taxRegion == Region::DTD && $grandTotal > 100000000) {
                $this->forwardToCG = true;
            }


            $this->exitMinutes = $this->subject->exit_minutes;
            $this->finalReport = $this->subject->final_report;
            $this->workingReport = $this->subject->working_report;
            $this->preliminaryReport = $this->subject->preliminary_report;
            $this->entryMeeting = $this->subject->entry_minutes;
            $this->notificationLetter = $this->subject->notification_letter;

            if ($this->task != null) {
                $operators = json_decode($this->task->operators);
                if (gettype($operators) != "array") {
                    $operators = [];
                }
                $roles = User::whereIn('id', $operators)->get()->pluck('role_id')->toArray();

                $this->subRoles = Role::whereIn('report_to', $roles)->get();

                $this->staffs = User::whereIn('role_id', $this->subRoles->pluck('id')->toArray())->get();
                // TODO: Remove on production
                $this->staffs = User::get();
            }
        }
    }



    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('assign_officers')) {
            $this->validate(
                [
                    'periodFrom' => 'required|date',
                    'periodTo' => 'required|date|after:periodFrom',
                    'auditingDate' => 'required|date|after:today',
                    'intension' => 'required|strip_tag',
                    'scope' => 'required|strip_tag',
                    'teamLeader' => ['required',  new NotIn([$this->teamMember])],
                    'teamMember' => ['required',  new NotIn([$this->teamLeader])],
                ],
                [
                    'teamLeader.not_in' => 'Duplicate  already exists as team member',
                    'teamMember.not_in' => 'Duplicate already exists as team leader'
                ]
            );
        }

        if ($this->checkTransition('send_notification_letter')) {
            $this->validate(
                [
                    'notificationLetter' => 'required|max:1024',
                ]
            );

            if ($this->notificationLetter != $this->subject->notification_letter) {
                $this->validate([
                    'notificationLetter' => 'required|mimes:pdf|max:1024|max_file_name_length:100'
                ]);
            }
        }

        if ($this->checkTransition('conduct_audit')) {
            $this->validate(
                [
                    'preliminaryReport' => 'required|max:1024',
                    'workingReport' => 'required|max:1024',
                    'entryMeeting' => 'required|max:1024',
                ]
            );

            if ($this->preliminaryReport != $this->subject->preliminary_report) {
                $this->validate([
                    'preliminaryReport' => 'required|mimes:pdf|max:1024|max_file_name_length:100'
                ]);
            }

            if ($this->workingReport != $this->subject->working_report) {
                $this->validate([
                    'workingReport' => 'required|mimes:pdf|max:1024|max_file_name_length:100'
                ]);
            }
            if ($this->entryMeeting != $this->subject->entry_minutes) {
                $this->validate([
                    'entryMeeting' => 'required|mimes:pdf|max:1024|max_file_name_length:100'
                ]);
            }
        }

        if ($this->checkTransition('prepare_final_report')) {
            $this->validate([
                'finalReport' => 'required|max:1024',
                'exitMinutes' => 'required',
                'hasAssessment' => ['required', 'boolean'],
            ]);

            // Dynamically add validation rules for each tax type
            $taxTypes = explode(",", $this->audit->taxAuditTaxTypeNames());
            $validationRules = [];
            foreach ($taxTypes as $taxType) {
                $taxTypeKey = str_replace(' ', '_', $taxType);
                $validationRules["principalAmounts.{$taxTypeKey}"] = [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'regex:/^[0-9,]*$/'];
                $validationRules["interestAmounts.{$taxTypeKey}"] = [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'regex:/^[0-9,]*$/'];
                $validationRules["penaltyAmounts.{$taxTypeKey}"] = [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'regex:/^[0-9,]*$/'];
            }

            $this->validate($validationRules);


            if ($this->exitMinutes != $this->subject->exit_minutes) {
                $this->validate([
                    'exitMinutes' => 'required|mimes:pdf|max:1024|max_file_name_length:100'
                ]);
            }

            if ($this->finalReport != $this->subject->final_report) {
                $this->validate([
                    'finalReport' => 'required|mimes:pdf|max:1024|max_file_name_length:100'
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

            if ($this->checkTransition('send_notification_letter')) {

                $notificationLetter = $this->notificationLetter;
                if ($this->notificationLetter != $this->subject->notification_letter) {
                    $notificationLetter = $this->notificationLetter->store('audit', 'local');
                }

                $this->subject->notification_letter = $notificationLetter;
                $this->subject->save();

                //Send Email Notification to taxpayer 
                event(new SendMail('notification-letter-to-taxpayer', [$this->subject->business->taxpayer, $this->subject]));

                //Send SMS Notification to taxpayer 
                event(new SendSms('notification-letter-to-taxpayer', [$this->subject->business->taxpayer, $this->subject]));
            }

            //* Update the auditing date if a new audit date (Extension) is available and save the changes.
            if ($this->checkTransition('audit_team_review')) {
                if ($this->subject->new_audit_date) {
                    $this->subject->auditing_date = $this->subject->new_audit_date;
                }
                $this->subject->save();
            }

            if ($this->checkTransition('conduct_audit')) {

                $preliminaryReport = $this->preliminaryReport;
                if ($this->preliminaryReport != $this->subject->preliminary_report) {
                    $preliminaryReport = $this->preliminaryReport->store('audit', 'local');
                }

                $workingReport = $this->workingReport;
                if ($this->workingReport != $this->subject->working_report) {
                    $workingReport = $this->workingReport->store('audit', 'local');
                }

                $entryMeeting = $this->entryMeeting;
                if ($this->entryMeeting != $this->subject->entry_minutes) {
                    $entryMeeting = $this->entryMeeting->store('audit', 'local');
                }

                $this->subject->preliminary_report = $preliminaryReport;
                $this->subject->working_report = $workingReport;
                $this->subject->entry_minutes = $entryMeeting;
                $this->subject->save();
            }

            if ($this->checkTransition('preliminary_report_review')) {
                $operators = $this->subject->officers->pluck('user_id')->toArray();
            }

            if ($this->checkTransition('prepare_final_report')) {
                $assessment = $this->subject->assessment()->exists();

                if ($this->hasAssessment == "1") {
                    foreach ($this->principalAmounts as $taxTypeKey => $principalAmount) {
                        $interestAmount = str_replace(',', '', $this->interestAmounts[$taxTypeKey]);
                        $penaltyAmount = str_replace(',', '', $this->penaltyAmounts[$taxTypeKey]);
                        $principalAmount = str_replace(',', '', $principalAmount);
                        $taxTypeId = $this->taxTypeIds[$taxTypeKey];

                        $totalAmount = $penaltyAmount + $interestAmount + $principalAmount;


                        if ($assessment) {
                            $this->subject->assessment()->where('tax_type_id', $taxTypeId)->update([
                                'principal_amount' => $principalAmount,
                                'interest_amount' => $interestAmount,
                                'penalty_amount' => $penaltyAmount,
                                'total_amount' => $totalAmount,
                                'outstanding_amount' => $totalAmount,
                                'original_principal_amount' => $principalAmount,
                                'original_interest_amount' => $interestAmount,
                                'original_penalty_amount' => $penaltyAmount,
                                'original_total_amount' => $totalAmount
                            ]);
                        } else {
                            TaxAssessment::create([
                                'location_id' => $this->subject->location_id,
                                'business_id' => $this->subject->business_id,
                                'tax_type_id' => $taxTypeId,
                                'assessment_id' => $this->subject->id,
                                'assessment_type' => get_class($this->subject),
                                'principal_amount' => $principalAmount,
                                'interest_amount' => $interestAmount,
                                'penalty_amount' => $penaltyAmount,
                                'total_amount' => $totalAmount,
                                'outstanding_amount' => $totalAmount,
                                'original_principal_amount' => $principalAmount,
                                'original_interest_amount' => $interestAmount,
                                'original_penalty_amount' => $penaltyAmount,
                                'original_total_amount' => $totalAmount
                            ]);
                        }
                    }
                } else {
                    if ($assessment) {
                        $this->subject->assessment()->delete();
                    }
                }

                $exitMinutes = $this->exitMinutes;
                if ($this->exitMinutes != $this->subject->exit_minutes) {
                    $exitMinutes = $this->exitMinutes->store('audit', 'local');
                }

                $finalReport = $this->finalReport;
                if ($this->finalReport != $this->subject->final_report) {
                    $finalReport = $this->finalReport->store('audit', 'local');
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

                    if ($auditManagerRole) {
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
            }


            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments, 'operators' => $operators]);
            DB::commit();

            if ($this->subject->status == TaxAuditStatus::APPROVED && $this->subject->assessment()->exists()) {
                $this->generateControlNumber();

                foreach ($this->taxAssessments as $taxAssessment) {
                    $taxAssessment->update([
                        'payment_due_date' => Carbon::now()->addDays(30)->toDateTimeString(),
                        'curr_payment_due_date' => Carbon::now()->addDays(30)->toDateTimeString(),
                    ]);
                }

                event(new SendMail('audit-approved-notification', $this->subject->business->taxpayer));
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function generateControlNumber()
    {
        foreach ($this->taxAssessments as $taxAssessment) {

            $assessment = $taxAssessment;
            $taxType = null;
            if ($this->subject->tax_type_id == 0) {
                if ($assessment->assessment_type == TaxAudit::class) {
                    $taxType = $assessment->assessment_type::find($assessment->assessment_id)->taxAuditTaxTypeNames();
                    if (is_null($taxType)) {
                        abort(404);
                    }
                } else if ($assessment->assessment_type == TaxInvestigation::class) {
                    $taxType = $assessment->assessment_type::find($assessment->assessment_id)->taxInvestigationTaxTypeNames();
                    if (is_null($taxType)) {
                        abort(404);
                    }
                }
            } else {
                $taxType = $this->subject->taxType;
            }

            $billitems = [
                [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => roundOff($assessment->principal_amount, 'TZS'),
                    'currency' => 'TZS',
                    'gfs_code' => $assessment->taxtype->where('code', 'audit')->firstOrFail()->gfs_code,
                    'tax_type_id' => $assessment->taxtype->where('code', 'audit')->firstOrFail()->id
                ],
                [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => roundOff($assessment->interest_amount, 'TZS'),
                    'currency' => 'TZS',
                    'gfs_code' => $assessment->taxtype->where('code', 'audit')->firstOrFail()->gfs_code,
                    'tax_type_id' => $assessment->taxtype->where('code', 'interest')->firstOrFail()->id
                ],
                [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => roundOff($assessment->penalty_amount, 'TZS'),
                    'currency' => 'TZS',
                    'gfs_code' => $assessment->taxtype->where('code', 'audit')->firstOrFail()->gfs_code,
                    'tax_type_id' => $assessment->taxtype->where('code', 'penalty')->firstOrFail()->id
                ]
            ];
        }


        DB::beginTransaction();

        try {

            $taxpayer = $this->subject->business->taxpayer;

            $payer_type = get_class($taxpayer);
            $payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
            $payer_email = $taxpayer->email;
            $payer_phone = $taxpayer->mobile;
            $description = "Auditing for {$taxType}";
            $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
            $currency = 'TZS';
            $createdby_type = get_class(Auth::user());
            $createdby_id = Auth::id();
            $exchange_rate = 1;
            $payer_id = $taxpayer->id;
            $expire_date = Carbon::now()->addDays(30)->toDateTimeString();
            $billableId = $assessment->id; //TODO: need to be disscussed
            $billableType = get_class($assessment);

            $zmBill = ZmCore::createBill(
                $billableId,
                $billableType,
                $this->taxTypes->where('code', 'audit')->first()->id,  //TODO: need to be disscussed
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
            DB::commit();

            if (config('app.env') != 'local') {
                $this->generateGeneralControlNumber($zmBill);
            } else {
                // We are local
                $assessment->payment_status = ReturnStatus::CN_GENERATED;
                $assessment->save();

                // Simulate successful control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = 'pending';
                $zmBill->control_number = random_int(2000070001000, 2000070009999);
                $zmBill->save();

                $this->customAlert('success', 'A control number for this verification has been generated successfully');
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            throw $e;
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

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        try {
            $operators = [];
            if ($this->checkTransition('correct_preliminary_report')) {
                $operators = $this->subject->officers->pluck('user_id')->toArray();
            }
            if ($this->checkTransition('correct_final_report')) {
                $operators = $this->subject->officers->pluck('user_id')->toArray();
            }

            $this->doTransition($transition, ['status' => 'reject', 'comment' => $this->comments, 'operators' => $operators]);
        } catch (Exception $e) {
            Log::error($e);

            return;
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }

    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->customAlert('warning', 'Are you sure you want to complete this action?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => $action,
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'transition' => $transition
            ],

        ]);
    }

    public function render()
    {
        return view('livewire.approval.tax_audit');
    }
}
