<?php

namespace App\Http\Livewire\Approval;

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
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ReturnDebtWaiverApprovalProcessing extends Component
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
        $this->modelId = decrypt($modelId);
        $this->debt_waiver = DebtWaiver::find($this->modelId);
        $this->debt = $this->debt_waiver->debt;
        $this->taxTypes = TaxType::all();
        $this->registerWorkflow($modelName, $this->modelId);
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
            $this->penaltyAmount = ($this->debt->penalty * $this->penaltyPercent) / 100;
        }

        if ($propertyName == "interestPercent") {
            if ($this->interestPercent > 50) {
                $this->interestPercent = 50;
            } elseif ($this->interestPercent < 0 || !is_numeric($this->interestPercent)) {
                $this->interestPercent = null;
            }
            $this->interestAmount = ($this->debt->interest * $this->interestPercent) / 100;
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
    
                    $now = Carbon::now();
                    if ($this->debt->bill) {
                        CancelBill::dispatch($this->debt->bill, 'Debt has been waived')->delay($now->addSeconds(10));
                        GenerateControlNo::dispatch($this->debt)->delay($now->addSeconds(10));
                    } else {
                        GenerateControlNo::dispatch($this->debt)->delay($now->addSeconds(10));
                    }

                    $notification_payload = [
                        'debt' => $this->debt,
                    ];

                    event(new SendSms('debt-waiver-approval', $notification_payload));
                    event(new SendMail('debt-waiver-approval', $notification_payload));
    
                    DB::commit();
                } catch (Exception $e) {
                    Log::error($e);
                    DB::rollBack();
                    $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
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

                $now = Carbon::now();
                $this->subject->status = WaiverStatus::APPROVED;
                $this->subject->save();

                if ($this->debt->bill) {
                    CancelBill::dispatch($this->debt->bill, 'Debt has been waived')->delay($now->addSeconds(10));
                    GenerateControlNo::dispatch($this->debt)->delay($now->addSeconds(10));
                } else {
                    GenerateControlNo::dispatch($this->debt)->delay($now->addSeconds(10));
                }

                $notification_payload = [
                    'debt' => $this->debt,
                ];

                event(new SendSms('debt-waiver-approval', $notification_payload));
                event(new SendMail('debt-waiver-approval', $notification_payload));

                DB::commit();
            } catch (Exception $e) {
                Log::error($e);
                DB::rollBack();
                $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
            }

        }

        try {
            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?.');
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        if (!Gate::allows('debt-management-debts-waive')) {
            abort(403);
        }
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

                $notification_payload = [
                    'debt' => $this->debt,
                ];

                event(new SendSms('debt-waiver-rejected', $notification_payload));
                event(new SendMail('debt-waiver-rejected', $notification_payload));
            }

            if ($this->checkTransition('commisioner_reject')) {
                $this->validate([
                    'comments' => 'required',
                ]);
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
            $this->alert('error', 'Something went wrong, Could you please contact our administrator for assistance?');
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
        $this->alert('warning', 'Are you sure you want to complete this action?', [
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
