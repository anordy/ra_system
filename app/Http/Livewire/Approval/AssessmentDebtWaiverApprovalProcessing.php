<?php

namespace App\Http\Livewire\Approval;

use Exception;
use Carbon\Carbon;
use App\Models\TaxType;
use Livewire\Component;
use App\Models\WaiverStatus;
use App\Jobs\Bill\CancelBill;
use App\Jobs\Debt\GenerateAssessmentDebtControlNo;
use App\Traits\PaymentsTrait;
use Livewire\WithFileUploads;
use App\Models\Debts\DebtWaiver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\Debt\GenerateControlNo;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AssessmentDebtWaiverApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, WithFileUploads, PaymentsTrait, LivewireAlert;
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
        $this->modelId = $modelId;
        $this->debt_waiver = DebtWaiver::find($this->modelId);
        $this->debt = $this->debt_waiver->debt;
        $this->taxTypes = TaxType::all();
        $this->registerWorkflow($modelName, $modelId);
        $this->forwardToCommisioner = $this->canForwardToCommisioner($this->debt);
    }

    public function updated($propertyName)
    {
        if ($propertyName == "penaltyPercent") {
            if ($this->penaltyPercent > 100) {
                $this->penaltyPercent = 100;
            } elseif ($this->penaltyPercent < 0 || !is_numeric($this->penaltyPercent)) {
                $this->penaltyPercent = null;
            }
            $this->penaltyAmount = ($this->debt->penalty_amount * $this->penaltyPercent) / 100;
        }

        if ($propertyName == "interestPercent") {
            if ($this->interestPercent > 50) {
                $this->interestPercent = 50;
            } elseif ($this->interestPercent < 0 || !is_numeric($this->interestPercent)) {
                $this->interestPercent = null;
            }
            $this->interestAmount = ($this->debt->interest_amount * $this->interestPercent) / 100;
        }

        $this->penaltyAmountDue = $this->debt->penalty_amount - $this->penaltyAmount;
        $this->interestAmountDue = $this->debt->interest_amount - $this->interestAmount;
        $this->total = ($this->penaltyAmountDue + $this->interestAmountDue + $this->debt->principal_amount);

        $this->penaltyAmountDue = round($this->penaltyAmountDue, 2);
        $this->interestAmountDue = round($this->interestAmountDue, 2);
        $this->total = round($this->total, 2);
    }

    public function approve($transtion)
    {

        if ($this->checkTransition('debt_manager_review')) {

        }

        if ($this->checkTransition('crdm_complete')) {
            if (!$this->forwardToCommisioner) {
                $this->validate([
                    'interestPercent' => 'required',
                    'penaltyPercent' => 'required',
                ]);
                DB::beginTransaction();
                try {
                    $this->debt_waiver->update([
                        'penalty_rate' => $this->penaltyPercent ?? 0,
                        'interest_rate' => $this->interestPercent ?? 0
                    ]);

                    $this->debt->update([
                        'penalty_amount' => $this->penaltyAmountDue,
                        'interest_amount' => $this->interestAmountDue,
                        'total_amount' => $this->total,
                        'outstanding_amount' => $this->total,
                        'application_status' => 'waiver',
                    ]);

                    $this->subject->status = WaiverStatus::APPROVED;
                    $this->subject->save();
    
                    $now = Carbon::now();
                    if ($this->debt->bill) {
                        CancelBill::dispatch($this->debt->bill, 'Debt has been waived')->delay($now->addSeconds(10));
                        GenerateAssessmentDebtControlNo::dispatch($this->debt)->delay($now->addSeconds(10));
                    } else {
                        GenerateAssessmentDebtControlNo::dispatch($this->debt)->delay($now->addSeconds(10));
                    }
    
                    DB::commit();
                } catch (Exception $e) {
                    Log::error($e);
                    DB::rollBack();
                    $this->alert('error', 'Something went wrong');
                }
    
            }
       
        }

        if ($this->checkTransition('commisioner_complete')) {
            $this->validate([
                'interestPercent' => 'required',
                'penaltyPercent' => 'required',
            ]);
            DB::beginTransaction();
            try {
                $this->debt_waiver->update([
                    'penalty_rate' => $this->penaltyPercent ?? 0,
                    'interest_rate' => $this->interestPercent ?? 0
                ]);

                $this->debt->update([
                    'penalty_amount' => $this->penaltyAmountDue,
                    'interest_amount' => $this->interestAmountDue,
                    'total_amount' => $this->total,
                    'outstanding_amount' => $this->total,
                    'application_status' => 'waiver',
                ]);

                $now = Carbon::now();
                $this->subject->status = WaiverStatus::APPROVED;
                $this->subject->save();

                if ($this->debt->bill) {
                    CancelBill::dispatch($this->debt->bill, 'Debt has been waived')->delay($now->addSeconds(10));
                    GenerateAssessmentDebtControlNo::dispatch($this->debt)->delay($now->addSeconds(10));
                } else {
                    GenerateAssessmentDebtControlNo::dispatch($this->debt)->delay($now->addSeconds(10));
                }

                DB::commit();
            } catch (Exception $e) {
                Log::error($e);
                DB::rollBack();
                $this->alert('error', 'Something went wrong');
            }

        }

        try {
            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong.');
        }
    }

    public function reject($transtion)
    {

        try {
            if ($this->checkTransition('application_filled_incorrect')) {
                $this->subject->status = WaiverStatus::CORRECTION;
                // event(new SendSms('business-registration-correction', $this->subject->id));
                // event(new SendMail('business-registration-correction', $this->subject->id));
            }

            if ($this->checkTransition('crdm_reject')) {
                $this->validate([
                    'comments' => 'required',
                ]);
                $this->subject->status = WaiverStatus::REJECTED;
                $this->debt->update(['application_status' => 'normal']);
                $this->subject->save();
            }

            if ($this->checkTransition('commisioner_reject')) {
                $this->validate([
                    'comments' => 'required',
                ]);
                $this->subject->status = WaiverStatus::REJECTED;
                $this->debt->update(['application_status' => 'normal']);
                $this->subject->save();
            }

            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');
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

    public function render()
    {
        return view('livewire.approval.assessment-debt-waiver-approval-processing');
    }

}
