<?php

namespace App\Traits;

use App\Enum\BillStatus;
use App\Enum\DisputeStatus;
use App\Enum\InstallmentStatus;
use App\Enum\LeaseStatus;
use App\Enum\TaxAssessmentStatus;
use App\Models\Installment\InstallmentItem;
use App\Models\LandLeaseDebt;
use App\Models\LeasePayment;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\TaxReturn;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait AfterPaymentEvents
{
    use TaxVerificationTrait, LandLeaseTrait;

    private $billable = [
        PortReturn::class,
        LeasePayment::class,
    ];

    private $installable = [
        InstallmentItem::class,
    ];

    private function updateBillable($bill)
    {
        try {
            if (in_array($bill->billable_type, $this->billable)) {
                if ($bill->paidAmount() >= $bill->amount) {
                    $billable = $bill->billable;
                    $billable->status = ReturnStatus::COMPLETE;
                    $billable->paid_at = Carbon::now()->toDateTimeString();
                    $billable->save();
                } else {
                    $billable = $bill->billable;
                    $billable->status = ReturnStatus::PAID_PARTIALLY;
                    $billable->save();
                }
            }
        } catch (\Exception $exception) {
            Log::error($exception);
        }
    }


    private function updateTaxReturn($bill)
    {
        try {
            if ($bill->billable_type == TaxReturn::class) {
                if ($bill->paidAmount() >= $bill->amount) {
                    $tax_return = $bill->billable;
                    $return = $tax_return->return;
                    if ($return) {
                        $return->status = ReturnStatus::COMPLETE;
                        $return->paid_at = Carbon::now()->toDateTimeString();
                        $return->save();

                        // Trigger verifications approval
                        $this->initiateVerificationApproval($return);
                    }
                    $tax_return->payment_status = ReturnStatus::COMPLETE;
                    $tax_return->outstanding_amount = 0;
                    $tax_return->save();
                } else {
                    $tax_return = $bill->billable;
                    $tax_return->status = ReturnStatus::PAID_PARTIALLY;
                    $tax_return->outstanding_amount = $bill->amount - $bill->paidAmount();
                    $tax_return->save();
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }


    private function updateInstallment($bill)
    {
        try {
            if ($bill->billable_type == InstallmentItem::class) {
                if ($bill->paidAmount() >= $bill->amount) {
                    $item = $bill->billable;
                    $item->update([
                        'status' => ReturnStatus::COMPLETE,
                        'paid_at' => Carbon::now()->toDateTimeString(),
                    ]);

                    $installable = $item->installment->installable;
                    $installable->update([
                        'outstanding_amount' => $installable->outstanding_amount - $bill->amount,
                    ]);

                    if ($item->installment->getNextPaymentDate()) {
                        $installable->update([
                            'curr_payment_due_date' => $item->installment->getNextPaymentDate(),
                        ]);
                    } elseif (!$item->installment->getNextPaymentDate() && ($item->installment->status == InstallmentStatus::ACTIVE)) {
                        $item->installment->update([
                            'status' => InstallmentStatus::COMPLETE,
                        ]);

                        $item->installment->installable->update([
                            'status' => ReturnStatus::COMPLETE,
                        ]);

                        $item->installment->installable->return->update([
                            'status' => ReturnStatus::COMPLETE,
                        ]);
                    }
                } else {
                    $item = $bill->billable;
                    $item->update([
                        'status' => ReturnStatus::PAID_PARTIALLY,
                    ]);

                    $installable = $item->installment->installable;
                    $installable->update([
                        'outstanding_amount' => $installable->outstanding_amount - $bill->amount,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }



    private function updateAssessment($bill)
    {
        try {
            $assessmentBillItems = $bill->bill_items->pluck('billable_type')->toArray();
            if ($bill->billable_type == TaxAssessment::class && in_array(Dispute::class, $assessmentBillItems)) {
                if ($bill->paidAmount() >= $bill->amount) {
                    $dispute = $bill->bill_items()->where('billable_type', Dispute::class)->firstOrFail()->billable;

                    $assessment = $bill->billable;
                    
                    if ($assessment->app_status == TaxAssessmentStatus::WAIVER_AND_OBJECTION) {

                        $assessment->payment_status = BillStatus::COMPLETE;
                        $assessment->save();
                        
                    } else {
                        $assessment->payment_status = BillStatus::PAID_PARTIALLY;
                        $assessment->save();

                        $this->registerWorkflow(get_class($dispute), $dispute->id);
                        $this->doTransition('application_submitted', []);
                        $dispute->app_status = DisputeStatus::SUBMITTED;
                    }

                    $dispute->payment_status = BillStatus::COMPLETE;
                    $dispute->save();

                    
                }
            }elseif ($bill->billable_type == TaxAssessment::class ){
                if ($bill->paidAmount() >= $bill->amount) {
                    $assessment = $bill->billable;
                    $assessment->payment_status = BillStatus::COMPLETE;
                    $assessment->save();
                } else {
                    $assessment = $bill->billable;
                    $assessment->payment_status = BillStatus::PAID_PARTIALLY;
                    $assessment->save();
                }
            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }



    private function updateLeasePayment($bill)
    {

        try {
            if ($bill->billable_type == LeasePayment::class) {
                $updateLeasePayment = $bill->billable;

                if ($bill->paidAmount() >= $bill->amount) {

                    $nowDate = Carbon::now();
                    $due_date = Carbon::parse($updateLeasePayment->due_date);

                    if ($nowDate->month == $due_date->month && $nowDate->year == $due_date->year) {
                        $status = LeaseStatus::ON_TIME_PAYMENT;
                    } elseif ($nowDate < $due_date && $nowDate->year <= $due_date->year) {
                        $status = LeaseStatus::IN_ADVANCE_PAYMENT;
                    } elseif ($nowDate > $due_date) {
                        $status = LeaseStatus::LATE_PAYMENT;
                    }
                    $updateLeasePayment->status = $status;
                } else {

                    $updateLeasePayment->status = LeaseStatus::PAID_PARTIALLY;
                }

                $updateLeasePayment->outstanding_amount = $bill->amount - $bill->paidAmount();
                $updateLeasePayment->paid_at = Carbon::now();
                $updateLeasePayment->save();

                if ($updateLeasePayment->debt) {
                    $updateDebt = LandLeaseDebt::find($updateLeasePayment->debt->id);
                    $updateDebt->status = LeaseStatus::COMPLETE;
                    $updateDebt->outstanding_amount = $updateLeasePayment->outstanding_amount;
                    $updateDebt->save();
                }

            }
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

}