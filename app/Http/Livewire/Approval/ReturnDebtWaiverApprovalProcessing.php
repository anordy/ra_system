<?php

namespace App\Http\Livewire\Approval;

use App\Enum\DebtWaiverCategory;
use App\Enum\TransactionType;
use App\Models\Returns\TaxReturn;
use App\Traits\VerificationTrait;
use Exception;
use Carbon\Carbon;
use App\Events\SendSms;
use App\Models\TaxType;
use Livewire\Component;
use App\Events\SendMail;
use App\Models\WaiverStatus;
use App\Jobs\Bill\CancelBill;
use App\Traits\PaymentsTrait;
use Livewire\WithFileUploads;
use App\Models\Debts\DebtWaiver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\Debt\GenerateControlNo;
use Illuminate\Support\Facades\Gate;
use App\Traits\WorkflowProcesssingTrait;
use App\Traits\CustomAlert;

class ReturnDebtWaiverApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, WithFileUploads, PaymentsTrait, CustomAlert, VerificationTrait;
    public $modelId;
    public $debt;
    public $modelName;
    public $comments;
    public $waiverReport;
    public $taxTypes;
    public $penaltyPercent = 0, $penaltyAmount = 0, $penaltyAmountDue = 0, $interestAmountDue = 0;
    public $interestPercent = 0, $interestAmount = 0, $debt_waiver, $total;
    public $natureOfAttachment, $noticeReport, $settingReport;
    public $forwardToCommisioner;

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = decrypt($modelId);
        $this->debt_waiver = DebtWaiver::find($this->modelId);
        if (is_null($this->debt_waiver)) {
            abort(404);
        }
        $this->debt = $this->debt_waiver->debt;
        $this->taxTypes = TaxType::all();
        $this->registerWorkflow($modelName, $this->modelId);
        $this->forwardToCommisioner = $this->canForwardToCommisioner($this->debt);
        $this->penaltyPercent = $this->debt_waiver->penalty_rate ?? 0;
        $this->interestPercent = $this->debt_waiver->interest_rate ?? 0;

        $this->penaltyAmount = roundOff(($this->debt->penalty * $this->penaltyPercent) / 100, $this->debt->currency);
        $this->interestAmount = roundOff(($this->debt->interest * $this->interestPercent) / 100, $this->debt->currency);
        $this->penaltyAmountDue = $this->debt->penalty - $this->penaltyAmount;
        $this->interestAmountDue = $this->debt->interest - $this->interestAmount;
        $this->total = ($this->penaltyAmountDue + $this->interestAmountDue + $this->debt->principal);
        $this->total = round($this->total, 2);

    }

    public function updated($propertyName)
    {
        if ($propertyName == "penaltyPercent") {
            if ($this->penaltyPercent > 100) {
                $this->penaltyPercent = 100;
            } elseif ($this->penaltyPercent < 0 || !is_numeric($this->penaltyPercent)) {
                $this->penaltyPercent = null;
            }
            $this->penaltyAmount = roundOff(($this->debt->penalty * $this->penaltyPercent) / 100, $this->debt->currency);
        }

        if ($propertyName == "interestPercent") {
            if ($this->interestPercent > 50) {
                $this->interestPercent = 50;
            } elseif ($this->interestPercent < 0 || !is_numeric($this->interestPercent)) {
                $this->interestPercent = null;
            }
            $this->interestAmount = roundOff(($this->debt->interest * $this->interestPercent) / 100, $this->debt->currency);
        }

        $this->penaltyAmountDue = $this->debt->penalty - $this->penaltyAmount;
        $this->interestAmountDue = $this->debt->interest - $this->interestAmount;
        $this->total = ($this->penaltyAmountDue + $this->interestAmountDue + $this->debt->principal);

        $this->penaltyAmountDue = round($this->penaltyAmountDue, 2);
        $this->interestAmountDue = round($this->interestAmountDue, 2);
        $this->total = round($this->total, 2);
    }

    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        if (!Gate::allows('debt-management-debts-waive')) {
            abort(403);
        }
        
        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);
        
        if ($this->checkTransition('debt_manager_review')) {

        }

        if ($this->checkTransition('department_commissioner_review')) {
            if ($this->debt_waiver->category === DebtWaiverCategory::INTEREST) {
                $this->validate([
                    'interestPercent' => 'required|numeric|min:1|max:50',
                ]);
            } else if ($this->debt_waiver->category === DebtWaiverCategory::PENALTY) {
                $this->validate([
                    'penaltyPercent' => 'required|numeric|min:1|max:100',
                ]);
            } else if ($this->debt_waiver->category === DebtWaiverCategory::BOTH) {
                $this->validate([
                    'interestPercent' => 'required|numeric|min:1|max:50',
                    'penaltyPercent' => 'required|numeric|min:1|max:100',
                ]);
            } else {
                $this->customAlert('warning', 'Invalid Debt Waiver Category');
                return;
            }


            DB::beginTransaction();
            try {
                $this->debt_waiver->update([
                    'penalty_rate' => $this->penaltyPercent ?? 0,
                    'interest_rate' => $this->interestPercent ?? 0
                ]);

                DB::commit();

            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
                return;
            }

        }

        if ($this->checkTransition('commissioner_general_complete')) {
            if ($this->debt_waiver->category === DebtWaiverCategory::INTEREST) {
                $this->validate([
                    'interestPercent' => 'required|numeric|min:1|max:50',
                ]);
            } else if ($this->debt_waiver->category === DebtWaiverCategory::PENALTY) {
                $this->validate([
                    'penaltyPercent' => 'required|numeric|min:1|max:100',
                ]);
            } else if ($this->debt_waiver->category === DebtWaiverCategory::BOTH) {
                $this->validate([
                    'interestPercent' => 'required|numeric|min:1|max:50',
                    'penaltyPercent' => 'required|numeric|min:1|max:100',
                ]);
            } else {
                $this->customAlert('warning', 'Invalid Debt Waiver Category');
                return;
            }


            DB::beginTransaction();
            try {
                $this->debt_waiver->update([
                    'penalty_rate' => $this->penaltyPercent ?? 0,
                    'interest_rate' => $this->interestPercent ?? 0,
                    'penalty_amount' => $this->penaltyAmount ?? 0,
                    'interest_amount' => $this->interestAmount ?? 0,
                ]);

                $this->debt->update([
                    'penalty' => $this->penaltyAmountDue,
                    'interest' => $this->interestAmountDue,
                    'total_amount' => $this->total,
                    'outstanding_amount' => $this->total,
                    'application_status' => 'waiver',
                ]);

                $this->subject->status = WaiverStatus::APPROVED;
                $this->subject->save();

                $notification_payload = [
                    'debt' => $this->debt,
                ];

                // Insert ledger credit for waiver
                $this->recordLedger(
                    TransactionType::CREDIT,
                    TaxReturn::class,
                    $this->debt->id,
                    $this->total,
                    0,
                    0,
                    $this->total,
                    $this->debt->tax_type_id,
                    $this->debt->currency,
                    $this->debt->business->taxpayer_id,
                    $this->debt->location_id,
                );

                DB::commit();

                event(new SendSms('debt-waiver-approval', $notification_payload));
                event(new SendMail('debt-waiver-approval', $notification_payload));

            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
                return;
            }

            try {
                if ($this->debt->bill) {
                    CancelBill::dispatch($this->debt->bill, 'Debt has been waived')->onQueue('high');
                    GenerateControlNo::dispatch($this->debt);
                } else {
                    GenerateControlNo::dispatch($this->debt);
                }
            } catch (Exception $e) {
                Log::error($e);
            }

        }

        try {
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help.');
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        if (!Gate::allows('debt-management-debts-waive')) {
            abort(403);
        }
        $this->validate([
            'comments' => 'required|strip_tag',
        ]);
        try {
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = WaiverStatus::CORRECTION;
                // event(new SendSms('business-registration-correction', $this->subject->id));
                // event(new SendMail('business-registration-correction', $this->subject->id));
            }

            if ($this->checkTransition('commissioner_general_reject')) {
                $this->subject->status = WaiverStatus::REJECTED;
                $this->debt->update(['application_status' => 'normal']);
                $this->subject->save();

                $notification_payload = [
                    'debt' => $this->debt,
                ];

                event(new SendSms('debt-waiver-rejected', $notification_payload));
                event(new SendMail('debt-waiver-rejected', $notification_payload));
            }

            if ($this->checkTransition('commissioner_reject')) {
                $this->subject->status = WaiverStatus::REJECTED;
                $this->debt->update(['application_status' => 'normal']);
                $this->subject->save();

                $notification_payload = [
                    'debt' => $this->debt,
                ];

                event(new SendSms('debt-waiver-rejected', $notification_payload));
                event(new SendMail('debt-waiver-rejected', $notification_payload));
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }

    public function canForwardToCommisioner($debt)
    {
        /**
         *  If CRDM: Forward waiver request with recommendation to the 
         *  Commissioner for a request that exceeds agreed amount 
         *  of USD 10,000 equivalent to TZS 20,000,000 otherwise don't forward the request and make decision
         */
        if ($debt->currency == 'TZS') {
            $amount_limiter = 20000000;
            $hasLimitExceeded = $debt->outstanding_amount > $amount_limiter ? true : false;
        } else if ($debt->currency == 'USD') {
            $amount_limiter = 10000;
            $hasLimitExceeded = $debt->outstanding_amount > $amount_limiter ? true : false;
        }
        return $hasLimitExceeded;

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
        return view('livewire.approval.return-debt-waiver-approval-processing');
    }

}