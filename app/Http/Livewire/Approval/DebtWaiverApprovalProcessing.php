<?php

namespace App\Http\Livewire\Approval;

use Exception;
use Carbon\Carbon;
use App\Models\TaxType;
use Livewire\Component;
use App\Models\Debts\Debt;
use App\Models\WaiverStatus;
use App\Jobs\Bill\CancelBill;
use App\Traits\PaymentsTrait;
use Livewire\WithFileUploads;
use App\Models\Debts\DebtWaiver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\Debt\GenerateControlNo;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class DebtWaiverApprovalProcessing extends Component
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
        $this->debt = Debt::find($this->debt_waiver->debt_id);
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
        $this->total = ($this->penaltyAmountDue + $this->interestAmountDue + $this->debt->principal_amount);
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
                    $this->debt->update([
                        'penalty' => $this->penaltyAmountDue,
                        'interest' => $this->interestAmountDue,
                        'total_amount' => $this->total,
                        'outstanding_amount' => $this->total,
                        'app_step' => 'waiver',
                    ]);
    
                    $billitems[] = [
                            'billable_id' => $this->debt->id,
                            'billable_type' => get_class($this->debt),
                            'use_item_ref_on_pay' => 'N',
                            'amount' => $this->debt->principal_amount,
                            'currency' => 'TZS',
                            'gfs_code' => $this->taxTypes->where('code', TaxType::DEBTS)->first()->gfs_code,
                            'tax_type_id' => $this->taxTypes->where('code', TaxType::DEBTS)->first()->id,
                    ];
    
                    if ($this->penaltyAmountDue > 0) {
                        $billitems[] = [
                            'billable_id' => $this->debt->id,
                            'billable_type' => get_class($this->debt),
                            'use_item_ref_on_pay' => 'N',
                            'amount' => $this->penaltyAmountDue,
                            'currency' => 'TZS',
                            'gfs_code' => $this->taxTypes->where('code', TaxType::PENALTY)->first()->gfs_code,
                            'tax_type_id' => $this->taxTypes->where('code', TaxType::PENALTY)->first()->id,
                        ];
                    }
      
                    if ($this->interestAmountDue > 0) {
                        $billitems[] = [
                            'billable_id' => $this->debt->id,
                            'billable_type' => get_class($this->debt),
                            'use_item_ref_on_pay' => 'N',
                            'amount' => $this->interestAmountDue,
                            'currency' => 'TZS',
                            'gfs_code' => $this->taxTypes->where('code', TaxType::INTEREST)->first()->gfs_code,
                            'tax_type_id' => $this->taxTypes->where('code', TaxType::INTEREST)->first()->id,
                        ];
                    }
                    $this->subject->status = WaiverStatus::APPROVED;
                    $this->subject->save();
    
                    $now = Carbon::now();
                    if ($this->debt->bill) {
                        CancelBill::dispatch($this->debt->bill, 'Debt has been waived')->delay($now->addSeconds(10));
                        GenerateControlNo::dispatch($this->debt)->delay($now->addSeconds(10));
                    } else {
                        GenerateControlNo::dispatch($this->debt)->delay($now->addSeconds(10));
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
                $this->debt->update([
                    'penalty' => $this->penaltyAmountDue,
                    'interest' => $this->interestAmountDue,
                    'total_amount' => $this->total,
                    'outstanding_amount' => $this->total,
                    'app_step' => 'waiver',
                ]);

                $billitems[] = [
                        'billable_id' => $this->debt->id,
                        'billable_type' => get_class($this->debt),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $this->debt->principal_amount,
                        'currency' => 'TZS',
                        'gfs_code' => $this->taxTypes->where('code', TaxType::DEBTS)->first()->gfs_code,
                        'tax_type_id' => $this->taxTypes->where('code', TaxType::DEBTS)->first()->id,
                ];

                if ($this->penaltyAmountDue > 0) {
                    $billitems[] = [
                        'billable_id' => $this->debt->id,
                        'billable_type' => get_class($this->debt),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $this->penaltyAmountDue,
                        'currency' => 'TZS',
                        'gfs_code' => $this->taxTypes->where('code', TaxType::PENALTY)->first()->gfs_code,
                        'tax_type_id' => $this->taxTypes->where('code', TaxType::PENALTY)->first()->id,
                    ];
                }
  
                if ($this->interestAmountDue > 0) {
                    $billitems[] = [
                        'billable_id' => $this->debt->id,
                        'billable_type' => get_class($this->debt),
                        'use_item_ref_on_pay' => 'N',
                        'amount' => $this->interestAmountDue,
                        'currency' => 'TZS',
                        'gfs_code' => $this->taxTypes->where('code', TaxType::INTEREST)->first()->gfs_code,
                        'tax_type_id' => $this->taxTypes->where('code', TaxType::INTEREST)->first()->id,
                    ];
                }

                $now = Carbon::now();
                $this->subject->status = WaiverStatus::APPROVED;
                $this->subject->save();

                if ($this->debt->bill) {
                    CancelBill::dispatch($this->debt->bill, 'Debt has been waived')->delay($now->addSeconds(10));
                    GenerateControlNo::dispatch($this->debt)->delay($now->addSeconds(10));
                } else {
                    GenerateControlNo::dispatch($this->debt)->delay($now->addSeconds(10));
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
            return;
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
                $this->debt->update(['app_step' => 'normal']);
                $this->subject->save();

            }

            if ($this->checkTransition('commisioner_reject')) {
                $this->validate([
                    'comments' => 'required',
                ]);
                $this->subject->status = WaiverStatus::REJECTED;
                $this->debt->update(['app_step' => 'normal']);
                $this->subject->save();
            }

            $this->doTransition($transtion, ['status' => 'agree', 'comment' => $this->comments]);
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong');

            return;
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
        return view('livewire.approval.debt-waiver-approval-processing');
    }

}
