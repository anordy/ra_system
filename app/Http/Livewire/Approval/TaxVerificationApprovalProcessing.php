<?php

namespace App\Http\Livewire\Approval;

use App\Enum\TaxVerificationStatus;
use App\Events\SendMail;
use App\Models\Returns\ReturnStatus;
use App\Models\Role;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Models\User;
use App\Models\Verification\TaxVerificationOfficer;
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

class TaxVerificationApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, WithFileUploads, PaymentsTrait;
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
        $this->modelId   = decrypt($modelId);
        $this->taxTypes = TaxType::all();
        $this->taxType = $this->taxTypes->firstWhere('code', TaxType::VERIFICATION);

        $this->registerWorkflow($modelName, $this->modelId);

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



    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);
        
        if ($this->checkTransition('conduct_verification')) {
            $this->validate(
                [
                    'principalAmount' => [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'numeric'],
                    'interestAmount' => [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'numeric'],
                    'penaltyAmount' => [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'numeric'],
                    'assessmentReport' => 'required|max:1024',
                ]
            );

            if ($this->assessmentReport != $this->subject->assessment_report) {
                $this->validate([
                    'assessmentReport' => 'required|mimes:pdf|max:1024|max_file_name_length:100'
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

        // TODO: Add this into transaction
        $operators = [];
        if ($this->checkTransition('assign_officers')) {

            $officers = $this->subject->officers()->exists();

            if ($officers) {
                $this->subject->officers()->delete();
            }

            TaxVerificationOfficer::create([
                'verification_id' => $this->subject->id,
                'user_id' => $this->teamLeader,
                'team_leader' => true,
            ]);

            TaxVerificationOfficer::create([
                'verification_id' => $this->subject->id,
                'user_id' => $this->teamMember,
            ]);

            $operators = [intval($this->teamLeader), intval($this->teamMember)];
        }

        if ($this->checkTransition('conduct_verification')) {
            $assessment = $this->subject->assessment()->exists();

            $this->principalAmount = roundOff($this->principalAmount, $this->subject->taxReturn->currency);
            $this->interestAmount = roundOff($this->interestAmount, $this->subject->taxReturn->currency);
            $this->penaltyAmount = roundOff($this->penaltyAmount, $this->subject->taxReturn->currency);
            if ($this->hasAssessment == "1") {
                if ($assessment) {
                    $this->subject->assessment()->update([
                        'principal_amount' => $this->principalAmount,
                        'interest_amount' => $this->interestAmount,
                        'penalty_amount' => $this->penaltyAmount,
                        'total_amount' => $this->principalAmount + $this->interestAmount + $this->penaltyAmount,
                        'outstanding_amount' => $this->penaltyAmount + $this->interestAmount + $this->principalAmount,
                        'original_principal_amount' => $this->principalAmount,
                        'original_interest_amount' => $this->interestAmount,
                        'original_penalty_amount' => $this->penaltyAmount,
                        'original_total_amount' => $this->principalAmount + $this->interestAmount + $this->penaltyAmount,
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
                        'outstanding_amount' => $this->principalAmount + $this->interestAmount + $this->penaltyAmount,
                        'total_amount' => $this->principalAmount + $this->interestAmount + $this->penaltyAmount,
                        'original_principal_amount' => $this->principalAmount,
                        'original_interest_amount' => $this->interestAmount,
                        'original_penalty_amount' => $this->penaltyAmount,
                        'original_total_amount' => $this->principalAmount + $this->interestAmount + $this->penaltyAmount,
                        'currency' => $this->subject->taxReturn->currency
                    ]);
                }
            } else {
                if ($assessment) {
                    $this->subject->assessment()->delete();
                }
            }

            $assessmentReport = $this->assessmentReport;
            if ($this->assessmentReport != $this->subject->assessment_report) {
                $assessmentReport = $this->assessmentReport->store('verification', 'local');
            }

            $this->subject->assessment_report = $assessmentReport;
            $this->subject->save();

            if ($this->assessmentReport != $this->subject->assessment_report) {
                event(new SendMail('send-assessment-report-to-taxpayer', [$this->subject->business->taxpayer, $this->subject]));
            }
        }
        DB::beginTransaction();
        try {
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments, 'operators' => $operators]);
            DB::commit();
            if ($this->subject->status == TaxVerificationStatus::APPROVED && $this->subject->assessment()->exists()) {
                $this->generateControlNumber();
                $this->subject->assessment->update([
                    'payment_due_date' => Carbon::now()->addDays(30)->endOfDay(),
                    'curr_payment_due_date' => Carbon::now()->addDays(30)->endOfDay(),
                ]);
            }

            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);
        Db::beginTransaction();
        try {
            $operators = [];
            if ($this->checkTransition('correct_verification_report')) {
                $operators = $this->subject->officers->pluck('user_id')->toArray();
            }
            $this->doTransition($transition, ['status' => 'reject', 'comment' => $this->comments, 'operators' => $operators]);
            DB::commit();
            $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }


    public function generateControlNumber()
    {
        $assessment = $this->subject->assessment;
        $taxType = $this->subject->taxType;

        DB::beginTransaction();

        try {
            $billitems = [];

            if ($this->principalAmount > 0) {
                $billitems[] = [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $assessment->principal_amount,
                    'currency' => $assessment->currency,
                    'gfs_code' => $taxType->gfs_code,
                    'tax_type_id' => $taxType->id
                ];
            }

            if ($this->interestAmount > 0) {
                $billitems[] = [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $assessment->interest_amount,
                    'currency' => $assessment->currency,
                    'gfs_code' => $taxType->gfs_code,
                    'tax_type_id' => $this->taxTypes->where('code', 'interest')->firstOrFail()->id
                ];
            }

            if ($this->penaltyAmount > 0) {
                $billitems[] = [
                    'billable_id' => $assessment->id,
                    'billable_type' => get_class($assessment),
                    'use_item_ref_on_pay' => 'N',
                    'amount' => $assessment->penalty_amount,
                    'currency' => $assessment->currency,
                    'gfs_code' => $taxType->gfs_code,
                    'tax_type_id' => $this->taxTypes->where('code', 'penalty')->firstOrFail()->id
                ];
            }


            $taxpayer = $this->subject->business->taxpayer;

            $payer_type = get_class($taxpayer);
            $payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
            $payer_email = $taxpayer->email;
            $payer_phone = $taxpayer->mobile;
            $description = "{$taxType->name} Verification Assessment for {$this->subject->business->name}";
            $payment_option = ZmCore::PAYMENT_OPTION_EXACT;
            $currency = $assessment->currency;
            $createdby_type = get_class(Auth::user());
            $createdby_id = Auth::id();
            $exchange_rate = $this->getExchangeRate($assessment->currency);
            $payer_id = $taxpayer->id;
            $expire_date = Carbon::now()->addDays(30)->endOfDay();
            $billableId = $assessment->id;
            $billableType = get_class($assessment);
            $taxType = $taxType->id;

            $zmBill = ZmCore::createBill(
                $billableId,
                $billableType,
                $taxType,
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
                $zmBill->control_number = rand(2000070001000, 2000070009999);
                $zmBill->save();
                $this->customAlert('success', 'A control number for this verification has been generated successfully');
            }
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
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
        return view('livewire.approval.tax_verification');
    }
}
