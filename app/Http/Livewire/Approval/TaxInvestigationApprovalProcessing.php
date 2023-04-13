<?php

namespace App\Http\Livewire\Approval;

use App\Enum\TaxInvestigationStatus;
use App\Models\CaseStage;
use App\Models\Investigation\TaxInvestigationOfficer;
use App\Models\LegalCase;
use App\Models\Returns\ReturnStatus;
use App\Models\Role;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Models\User;
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

class TaxInvestigationApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, WithFileUploads, PaymentsTrait;
    public $modelId;
    public $modelName;
    public $comments;

    public $teamLeader;
    public $teamMember;
    public $periodTo;
    public $periodFrom;

    public $principalAmount;
    public $interestAmount;
    public $penaltyAmount;
    public $investigationReport;
    public $workingsReport;
    public $intension;
    public $scope;
    public $exitMinutes;

    public $taxTypes;
    public $taxType;

    public $hasAssessment;


    public $staffs = [];
    public $subRoles = [];

    public $task;




    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->taxTypes = TaxType::all();
        $this->taxType = $this->taxTypes->firstWhere('code', TaxType::INVESTIGATION);

        $this->registerWorkflow($modelName, $this->modelId);

        $this->task = $this->subject->pinstancesActive;

        if(!isNullOrEmpty($this->subject->period_from)){
            $this->periodFrom = Carbon::create($this->subject->period_from)->format('Y-m-d');
        }
        if(!isNullOrEmpty($this->subject->period_to)){
            $this->periodTo = Carbon::create($this->subject->period_to)->format('Y-m-d');
        }

        $this->intension = $this->subject->intension;
        $this->scope = $this->subject->scope;
        $this->workingsReport = $this->subject->working_report;
        $this->investigationReport = $this->subject->investigation_report;

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


    public function hasNoticeOfAttachmentChange($value)
    {
        if ($value != "1") {
            $this->principalAmount = null;
            $this->interestAmount = null;
            $this->penaltyAmount = null;
        }
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        $operators = [];

        if ($this->checkTransition('assign_officers')) {
            $this->validate(
                [
                    'intension' => 'required|strip_tag',
                    'scope' => 'required|strip_tag',
                    'periodFrom' => 'required|date',
                    'periodTo' => 'required|after:periodFrom',
                    'teamLeader' => ['required',  new NotIn([$this->teamMember])],
                    'teamMember' => ['required',  new NotIn([$this->teamLeader])],
                ],
                [
                    'teamLeader.not_in' => 'Duplicate  already exists as team member',
                    'teamMember.not_in' => 'Duplicate already exists as team leader'
                ]
            );
        }

        if ($this->checkTransition('conduct_investigation')) {
            $this->validate(
                [
                    'hasAssessment' => ['required', 'boolean'],
                    'investigationReport' => ['required', 'max:1024'],
                    'workingsReport' => [new RequiredIf($this->hasAssessment == "1"), 'nullable'],
                    'interestAmount' => [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'regex:/^[\d\s,]*$/'],
                    'penaltyAmount' => [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'regex:/^[\d\s,]*$/'],
                ]
            );

            if ($this->workingsReport != $this->subject->working_report) {
                $this->validate([
                    'workingsReport' => 'required|mimes:pdf|max:1024|max_file_name_length:' . config('constants.file_name_length')
                ]);
            }

            if ($this->investigationReport != $this->subject->investigation_report) {
                $this->validate([
                    'investigationReport' => 'required|mimes:pdf|max:1024|max_file_name_length:' . config('constants.file_name_length')
                ]);
            }
        }

        DB::beginTransaction();
        try {

            if ($this->checkTransition('assign_officers')) {

                $officers = $this->subject->officers()->exists();

                if ($officers) {
                    $this->subject->officers()->delete();
                }

                TaxInvestigationOfficer::create([
                    'investigation_id' => $this->subject->id,
                    'user_id' => $this->teamLeader,
                    'team_leader' => true,
                ]);

                TaxInvestigationOfficer::create([
                    'investigation_id' => $this->subject->id,
                    'user_id' => $this->teamMember,
                ]);

                $this->subject->period_to = $this->periodTo;
                $this->subject->period_from = $this->periodTo;
                $this->subject->intension = $this->intension;
                $this->subject->scope = $this->scope;

                $this->subject->save();

                $operators = [intval($this->teamLeader), intval($this->teamMember)];
            }

            if ($this->checkTransition('conduct_investigation')) {

                $assessment = $this->subject->assessment()->exists();

                $principalAmount = $this->principalAmount ? str_replace(',', '', $this->principalAmount) : 0;
                $interestAmount = $this->interestAmount ? str_replace(',', '', $this->interestAmount) : 0;
                $penaltyAmount = $this->penaltyAmount ? str_replace(',', '', $this->penaltyAmount) : 0;
                $totalAMount = ($penaltyAmount + $interestAmount + $principalAmount);


                if ($this->hasAssessment == "1") {
                    if ($assessment) {
                        $this->subject->assessment()->update([
                            'principal_amount' => $principalAmount,
                            'interest_amount' => $interestAmount,
                            'penalty_amount' => $penaltyAmount,
                            'total_amount' => $totalAMount,
                            'outstanding_amount' => $totalAMount,
                            'original_principal_amount' => $principalAmount,
                            'original_interest_amount' => $interestAmount,
                            'original_penalty_amount' => $penaltyAmount,
                            'original_total_amount' => $principalAmount + $interestAmount + $penaltyAmount
                        ]);
                    } else {
                        TaxAssessment::create([
                            'location_id' => $this->subject->location_id,
                            'business_id' => $this->subject->business_id,
                            'tax_type_id' => $this->taxType->id,
                            'assessment_id' => $this->subject->id,
                            'assessment_type' => get_class($this->subject),
                            'principal_amount' => $principalAmount,
                            'interest_amount' => $interestAmount,
                            'penalty_amount' => $penaltyAmount,
                            'total_amount' => $totalAMount,
                            'outstanding_amount' => $totalAMount,
                            'original_principal_amount' => $principalAmount,
                            'original_interest_amount' => $interestAmount,
                            'original_penalty_amount' => $penaltyAmount,
                            'original_total_amount' => $principalAmount + $interestAmount + $penaltyAmount
                        ]);
                    }
                } else {
                    if ($assessment) {
                        $this->subject->assessment()->delete();
                    }
                }


                $investigationReport = "";
                if ($this->investigationReport != $this->subject->investigation_report) {
                    $investigationReport = $this->investigationReport->store('investigation', 'local');
                }

                $workingsReport = "";
                if ($this->workingsReport != $this->subject->working_report) {
                    $workingsReport = $this->investigationReport->store('investigation', 'local');
                }


                $this->subject->working_report = $workingsReport;
                $this->subject->investigation_report = $investigationReport;
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments, 'operators' => $operators]);
            
            DB::commit();

            if ($this->subject->status == TaxInvestigationStatus::LEGAL) {
                $this->addToLegalCase();
            }
    
            if ($this->subject->status == TaxInvestigationStatus::APPROVED && $this->subject->assessment()->exists()) {
                $this->generateControlNumber();
                $this->subject->assessment->update([
                    'payment_due_date' => Carbon::now()->addDays(30)->toDateTimeString(),
                    'curr_payment_due_date' => Carbon::now()->addDays(30)->toDateTimeString(),
                ]);
            }
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');

            return;
        }


     

    }

    public function addToLegalCase()
    {
        LegalCase::query()->create(
            [
                'tax_investigation_id' => $this->subject->id,
                'date_opened' => Carbon::now(),
                'case_number' => rand(0, 3),
                'case_details' => 'Added from Investigation Approval',
                'court' => 1,
                'case_stage_id' => CaseStage::query()->firstOrCreate(['name' => 'Case Opening'])->id ?? 1,
            ]
        );
    }

    public function generateControlNumber()
    {
        $assessment = $this->subject->assessment;

        DB::beginTransaction();

        try {
            $billitems = [
                [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $this->principalAmount,
                    'currency' => 'TZS',
                    'gfs_code' => $this->taxTypes->where('code', 'investigation')->firstOrFail()->gfs_code,
                    'tax_type_id' => $this->taxTypes->where('code', 'investigation')->firstOrFail()->id
                ],
                [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $this->interestAmount,
                    'currency' => 'TZS',
                    'gfs_code' => $this->taxTypes->where('code', 'interest')->firstOrFail()->gfs_code,
                    'tax_type_id' => $this->taxTypes->where('code', 'interest')->firstOrFail()->id
                ],
                [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $this->penaltyAmount,
                    'currency' => 'TZS',
                    'gfs_code' => $this->taxTypes->where('code', 'penalty')->firstOrFail()->gfs_code,
                    'tax_type_id' => $this->taxTypes->where('code', 'penalty')->firstOrFail()->id
                ]
            ];

            $taxpayer = $this->subject->business->taxpayer;

            $payer_type = get_class($taxpayer);
            $payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
            $payer_email = $taxpayer->email;
            $payer_phone = $taxpayer->mobile;
            $description = "Verification for {$this->taxType->name} ";
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
                $this->taxTypes->where('code', 'investigation')->firstOrFail()->id,
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

                // Simulate successfully control no generation
                $zmBill->zan_trx_sts_code = ZmResponse::SUCCESS;
                $zmBill->zan_status = 'pending';
                $zmBill->control_number = rand(2000070001000, 2000070009999);
                $zmBill->save();
                $this->customAlert('success', 'A control number for this verification has been generated successfully');
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
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
            if ($this->checkTransition('investigation_report')) {
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
        return view('livewire.approval.tax_investigation');
    }
}
