<?php

namespace App\Http\Livewire\Approval;

use App\Enum\GeneralConstant;
use App\Enum\TaxAuditStatus;
use App\Enum\TaxInvestigationStatus;
use App\Models\BusinessTaxType;
use App\Enum\TransactionType;
use App\Models\CaseStage;
use App\Models\Investigation\TaxInvestigationOfficer;
use App\Models\LegalCase;
use App\Models\Role;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Models\User;
use App\Rules\NotInArray;
use App\Traits\PaymentsTrait;
use App\Traits\TaxpayerLedgerTrait;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\NotIn;
use Illuminate\Validation\Rules\RequiredIf;
use App\Traits\CustomAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class TaxInvestigationApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert, WithFileUploads, PaymentsTrait, TaxpayerLedgerTrait;
    public $modelId;
    public $modelName;
    public $comments;
    public $teamLeader;
    public $teamMembers = [];
    public $periodTo;
    public $periodFrom;
    public $workingReport;
    public $noticeOfDiscussion;
    public $finalReport;
    public $preliminaryReport;
    public $investigationDocuments = [];
    public $taxAssessments = [];
    public $principalAmounts = [];
    public $interestAmounts = [];
    public $penaltyAmounts = [];
    public $currencies = [];
    public $taxTypeIds = [];
    public $allegations;
    public $descriptions;
    public $exitMinutes;
    public $taxTypes;
    public $taxType;
    public $hasAssessment;
    public $staffs = [];
    public $subRoles = [];
    public $task;
    public $investigation;
    public $extensionDate, $extensionReason;

    /**
     * Mounts the TaxInvestigationApprovalProcessing component,
     *  initializing its properties and registering the workflow.
     *
     * @param string $modelName The name of the model being processed.
     * @param string $modelId The encrypted ID of the model being processed.
     */
    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->taxTypes = TaxType::all();
        $this->taxType = $this->taxTypes->firstWhere('code', TaxType::INVESTIGATION);

        $this->registerWorkflow($modelName, $this->modelId);

        $this->subject = $this->getSubject();

        $this->exitMinutes = $this->subject->exit_minutes;
        $this->finalReport = $this->subject->final_report;
        $this->workingReport = $this->subject->working_report;
        $this->preliminaryReport = $this->subject->preliminary_report;
        $this->noticeOfDiscussion = $this->subject->notice_of_discussion;
        $this->allegations = $this->subject->intension;
        $this->descriptions = $this->subject->scope;
        if ($this->subject->extension_date) {
            $this->extensionDate = Carbon::create($this->subject->extension_date)->format('Y-m-d');
        }
        $this->extensionReason = $this->subject->extension_reason;
        $this->task = $this->subject->pinstancesActive;

        $this->periodFrom = $this->formatDate($this->subject->period_from);
        $this->periodTo = $this->formatDate($this->subject->period_to);

        $this->initializeAssessment();

        if ($this->checkTransition('conduct_investigation')) {
            $this->initializeTaxTypeAmounts();
        }

        if ($this->checkTransition('taxPayer_rejected_review')) {
            $this->investigationDocuments = DB::table('tax_investigations_files')->where('tax_investigation_id', $this->modelId)->get();
            $this->investigationDocuments = json_decode($this->investigationDocuments, true);
        }

        $this->initializeStaffAndRoles();
        $this->addSelect();
    }

    private function getSubject()
    {
        return app($this->modelName)->findOrFail($this->modelId);
    }

    private function formatDate($date)
    {
        return isNullOrEmpty($date) ? null : Carbon::create($date)->format('Y-m-d');
    }

    private function initializeAssessment()
    {
        $assessment = $this->subject->assessment;
        $this->hasAssessment = $assessment ? "1" : "0";

        if ($assessment) {
            $this->taxAssessments = TaxAssessment::where('assessment_id', $this->subject->id)
                ->where('assessment_type', get_class($this->subject))
                ->get();
        }
    }

    private function initializeTaxTypeAmounts()
    {
        $taxTypes = $this->subject->InvestigationTaxType();

        foreach ($taxTypes as $taxType) {
            $taxTypeKey = str_replace(' ', '_', $taxType['name']);
            $currency = BusinessTaxType::select('currency')->where('tax_type_id', $taxType['id'])->where('business_id', $this->subject->business_id)->firstOrFail()->currency;

            $this->taxTypeIds[$taxTypeKey] = $taxType['id'];
            $this->currencies[$taxTypeKey] = $currency;

            if (count($this->taxAssessments) > 0) {
                $assessment = $this->taxAssessments->where('tax_type_id', $taxType['id'])->firstOrFail();
                $this->principalAmounts[$taxTypeKey] = $assessment->principal_amount ?? GeneralConstant::ZERO_INT;
                $this->interestAmounts[$taxTypeKey] = $assessment->interest_amount ?? GeneralConstant::ZERO_INT;
                $this->penaltyAmounts[$taxTypeKey] = $assessment->penalty_amount ?? GeneralConstant::ZERO_INT;
            } else {
                $this->principalAmounts[$taxTypeKey] = null;
                $this->interestAmounts[$taxTypeKey] = null;
                $this->penaltyAmounts[$taxTypeKey] = null;
            }
        }
    }

    private function initializeStaffAndRoles()
    {
        if ($this->task != null) {
            $operators = json_decode($this->task->operators, true) ?: [];
            $users = User::whereIn('id', $operators)->get()->pluck('role_id')->toArray();
            $roles = Role::whereIn('id', $users)->get()->pluck('id')->toArray();
            $this->subRoles = Role::whereIn('report_to', $roles)->get();
            $this->staffs = User::whereIn('role_id', $this->subRoles->pluck('id')->toArray())->get();
        }
    }

    public function addSelect() {
        $this->teamMembers[] = ''; // Set the initial value to an empty string
    }

    public function removeSelect($index) {
        unset($this->teamMembers[$index]);
        $this->teamMembers = array_values($this->teamMembers); // Re-index the array
    }

    /**
     * Approves the tax investigation process and performs the necessary actions based on the transition.
     *
     * @param array $transition The transition data.
     * @throws Exception If an error occurs during the approval process.
     */
    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        $operators = [];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('assign_officers')) {
            $this->validateAssignOfficers();
        }

        if ($this->checkTransition('conduct_investigation')) {
            $this->validateConductInvestigation();
        }

        if ($this->checkTransition('final_report')) {
            $this->validateFinalReport();
        }

        DB::beginTransaction();
        try {
            if ($this->checkTransition('assign_officers')) {
                $operators =  $this->assignOfficers();
            }

            if ($this->checkTransition('conduct_investigation')) {
                $this->conductInvestigation();
            }
            if ($this->checkTransition('investigation_report_review')) {
                $this->subject->preliminary_report_date = Carbon::now()->addWeekdays(7); //add seven working days
                $this->subject->save();
            }

            if ($this->checkTransition('taxPayer_rejected_review')) {
                $operators = $this->subject->officers()->select('user_id')->pluck('user_id')->toArray() ?? [];
                $this->subject->was_rejected = True;
                $this->subject->save();
            }

            if ($this->checkTransition('final_report')) {

                $this->prepareFinalReport();
            }

            if ($this->checkTransition('extension_approved')) {
                $this->subject->preliminary_report_date = Carbon::create($this->extensionDate);
                $this->subject->save();
            }

            // $this->registerWorkflow($this->modelName, $this->modelId);
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments, 'operators' => $operators]);

            if ($this->subject->status == TaxAuditStatus::APPROVED && $this->subject->assessment()->exists()) {
                foreach ($this->subject->assessments as $key => $assessment) {
                    # code...
                    $assessment->update([
                        'payment_due_date' => Carbon::now()->addDays(30)->toDateTimeString(),
                        'curr_payment_due_date' => Carbon::now()->addDays(30)->toDateTimeString(),
                    ]);
                    $this->recordLedger(TransactionType::DEBIT, TaxAssessment::class, $assessment->id, $assessment->principal_amount, $assessment->interest_amount, $assessment->penalty_amount, $assessment->total_amount, $assessment->tax_type_id, $assessment->currency, $assessment->business->taxpayer_id, $assessment->location_id);
                }
            }

            DB::commit();

            $this->flash('success', __('Approved successfully'), [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', __('Something went wrong, please contact the administrator for help'));
        }
    }

    private function validateAssignOfficers()
    {
        $this->validate(
            [
                'allegations' => 'required|string',
                'descriptions' => 'required|string',
                'periodFrom' => 'required|date',
                'periodTo' => 'required|after:periodFrom',
                'teamLeader' => ['required', new NotInArray([$this->teamMembers])],
                'teamMembers.*' => ['required', new NotIn([$this->teamLeader])],
            ],
            [
                'teamLeader.not_in_array' => 'Duplicate already exists as team member',
                'teamMembers.*.not_in' => 'Duplicate already exists as team leader'
            ]
        );
    }

    private function validateConductInvestigation()
    {
        $this->validate([
            'preliminaryReport' => 'required|max:1024',
            'noticeOfDiscussion' => 'required|max:1024',
            'hasAssessment' => ['required', 'boolean'],
        ]);

        $validationRules = $this->generateTaxTypeValidationRules();
        $this->validate($validationRules);

        if ($this->preliminaryReport != $this->subject->preliminary_report) {
            $this->validate([
                'preliminaryReport' => 'required|mimes:pdf,csv|max:1024|max_file_name_length:100'
            ]);
        }

        if ($this->noticeOfDiscussion != $this->subject->notice_of_discussion) {
            $this->validate([
                'noticeOfDiscussion' => 'required|mimes:pdf,csv|max:1024|max_file_name_length:100'
            ]);
        }
    }
    private function validateFinalReport()
    {
        $this->validate([
            'finalReport' => 'required|max:1024',
            'workingReport' => 'nullable|max:1024',
        ]);

        if ($this->finalReport != $this->subject->final_report) {
            $this->validate([
                'finalReport' => 'required|mimes:pdf,csv|max:1024|max_file_name_length:100'
            ]);
        }

        if ($this->workingReport != $this->subject->working_report) {
            $this->validate([
                'workingReport' => 'nullable|mimes:pdf,csv|max:1024|max_file_name_length:100'
            ]);
        }
    }

    private function generateTaxTypeValidationRules()
    {
        $taxTypes = explode(",", $this->subject->taxInvestigationTaxTypeNames());
        $validationRules = [];
        foreach ($taxTypes as $taxType) {
            $taxTypeKey = str_replace(' ', '_', $taxType);
            $validationRules["principalAmounts.{$taxTypeKey}"] = [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'regex:/^[0-9,]*$/'];
            $validationRules["interestAmounts.{$taxTypeKey}"] = [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'regex:/^[0-9,]*$/'];
            $validationRules["penaltyAmounts.{$taxTypeKey}"] = [new RequiredIf($this->hasAssessment == "1"), 'nullable', 'regex:/^[0-9,]*$/'];
        }
        return $validationRules;
    }

    private function assignOfficers()
    {
        $officers = $this->subject->officers()->exists();

        if ($officers) {
            $this->subject->officers()->delete();
        }

        DB::beginTransaction();
        try {
            TaxInvestigationOfficer::create([
                'investigation_id' => $this->subject->id,
                'user_id' => $this->teamLeader,
                'team_leader' => true,
            ]);

            foreach ($this->teamMembers as $member){
                TaxInvestigationOfficer::create([
                    'investigation_id' => $this->subject->id,
                    'user_id' => $member,
                ]);
            }

            $this->subject->period_to = $this->periodTo;
            $this->subject->period_from = $this->periodTo;
            $this->subject->intension = $this->allegations;
            $this->subject->scope = $this->descriptions;
            $this->subject->save();
            DB::commit();
        }catch (Exception $ex){
            DB::rollBack();
            Log::error("ASSIGNING OFFICERS: ".$ex->getMessage());
        }


        $operators = array_merge(array($this->teamLeader), $this->teamMembers);
        return array_map('intval', $operators);
    }

    private function conductInvestigation()
    {
        $assessment = $this->subject->assessment()->exists();

        if ($this->hasAssessment == "1") {
            $this->handleAssessment($assessment);
        } else {
            if ($assessment) {
                $this->subject->assessment()->delete();
            }
        }

        $preliminaryReport = $this->preliminaryReport;
        if ($this->preliminaryReport != $this->subject->preliminary_report) {
            $preliminaryReport = $this->preliminaryReport->store('investigation', 'local');
        }

        $noticeOfDiscussion = $this->noticeOfDiscussion;
        if ($this->noticeOfDiscussion != $this->subject->notice_of_discussion) {
            $noticeOfDiscussion = $this->noticeOfDiscussion->store('investigation', 'local');
        }

        $this->subject->preliminary_report = $preliminaryReport;
        $this->subject->notice_of_discussion = $noticeOfDiscussion;
        $this->subject->save();
    }
    private function prepareFinalReport()
    {
        $finalReport = $this->finalReport;
        if ($this->finalReport != $this->subject->final_report) {
            $finalReport = $this->finalReport->store('investigation', 'local');
        }

        $workingReport = $this->workingReport;
        if ($this->workingReport != $this->subject->working_report) {
            $workingReport = $this->workingReport->store('investigation', 'local');
        }

//        todo: send email/sms notification
        $this->subject->final_report = $finalReport;
        $this->subject->working_report = $workingReport;
        $this->subject->save();
    }

    private function handleAssessment($assessment)
    {
        foreach ($this->principalAmounts as $taxTypeKey => $principalAmount) {
            $interestAmount = str_replace(',', '', $this->interestAmounts[$taxTypeKey]);
            $penaltyAmount = str_replace(',', '', $this->penaltyAmounts[$taxTypeKey]);
            $principalAmount = str_replace(',', '', $principalAmount);
            $taxTypeId = $this->taxTypeIds[$taxTypeKey];
            $totalAmount = $penaltyAmount + $interestAmount + $principalAmount;

            if ($assessment) {
                $this->updateAssessment($taxTypeId, $principalAmount, $interestAmount, $penaltyAmount, $totalAmount);
            } else {
                $this->createAssessment($taxTypeId, $principalAmount, $interestAmount, $penaltyAmount, $totalAmount);
            }
        }
    }

    private function updateAssessment($taxTypeId, $principalAmount, $interestAmount, $penaltyAmount, $totalAmount)
    {
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
    }

    private function createAssessment($taxTypeId, $principalAmount, $interestAmount, $penaltyAmount, $totalAmount)
    {
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

    public function addToLegalCase()
    {
        LegalCase::query()->create(
            [
                'tax_investigation_id' => $this->subject->id,
                'date_opened' => Carbon::now(),
                'case_number' => random_int(0, 3),
                'case_details' => 'Added from Investigation Approval',
                'court' => 1,
                'case_stage_id' => CaseStage::query()->firstOrCreate(['name' => 'Case Opening'])->id ?? 1,
            ]
        );
    }


    /**
     * Rejects the tax investigation approval process.
     *
     * @param array $transition The transition data for the approval process.
     * @return void
     */
    public function reject($transition)
    {
        // $transition = $transition['data']['transition'];
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
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return;
        }
        $this->flash('success', __('Rejected successfully'), [], redirect()->back()->getTargetUrl());
    }

    public function rejectExtension($transition){
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);
        $transition = $transition['data']['transition'];

        try {
            $this->doTransition($transition, ['status' => 'reject', 'comment' => $this->comments]);
            $this->flash('success', __('Rejected successfully'), [], redirect()->back()->getTargetUrl());
        }catch (Exception $ex){
            Log::error("EXTENSION REJECTED: ".$ex->getMessage());
            $this->flash('success', __('Rejected successfully'), [], redirect()->back()->getTargetUrl());
        }
    }

    protected $listeners = [
        'approve', 'reject', 'rejectExtension'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->customAlert('warning', __('Are you sure you want to complete this action?'), [
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
