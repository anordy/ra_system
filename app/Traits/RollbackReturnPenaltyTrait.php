<?php

namespace App\Traits;

use Exception;
use Carbon\Carbon;
use App\Jobs\Bill\CancelBill;
use App\Models\Returns\TaxReturn;
use App\Models\Debts\DebtRollback;
use App\Models\Returns\ReturnStatus;
use App\Models\TaxAssessments\TaxAssessment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

trait RollbackReturnPenaltyTrait
{

    use VerificationTrait;

    /**
     * Rollback latest return calculated penalty accumulated through a daily scheduled job.
     * 
     * APPLICATION: Tax Returns & Tax Assessments
     * 
     * SCENARIO:
     * When a taxpayer pays for a return on the last day of the due date, and zan malipo do not send back payment callback
     * such that the return bill remain unpaid and whenever the next penalty job runs it will calculate penalty since the return is
     * still unpaid in our system.
     * 
     * CONDITIONS FOR ROLLBACK:
     * 1. Before triggering this function the user must make sure the return was paid by either checking the reconciliation or
     *    recheck the bill has been resolved by zanmalipo payment callback
     * 2. Return's return_category must be either debt or overdue
     * 3. Return must have atleast penalty data in the debt_penalties table
     * 
     * SOLUTION: 
     * 1. When rolling back we soft delete the latest debt_penalties data and deduct the accumulated amount from the tax_returns table
     * marking the return as paid (We only support one rollback)
     */
    public function rollBackLatestReturnDebtPenalty($tax_return)
    {
        // Get the sencond latest bill
        $secondLatestBill = $tax_return->secondLatest->skip(1)->first();

        // Check if the bill has been paid in zm_bill & *double check from reconcilliations table
        if ($secondLatestBill == null) {
            throw new Exception('The previous bill does not exist');
        }

        if ($secondLatestBill->status == 'paid') {
            // Take the last debt penalty and soft delete it
            $last_debt_penalty = $tax_return->penalties->last();

            // Cancel the bill which had incremented penalty (last bill)
            $latestBill = $tax_return->latestBill;

            DB::beginTransaction();
            try {
                if (!$this->verify($tax_return)){
                    throw new Exception('Verification failed for tax return, please contact system admin.');
                }

                // Get the second last debt penalty, if we have two penalties get the first one
                if (count($tax_return->penalties) > 2) {
                    $second_last_debt_penalty = $tax_return->penalties->skip(1)->firstOrFail();
                    $tax_return->penalty = floatval($second_last_debt_penalty->penalty_amount) - floatval($second_last_debt_penalty->rate_amount);
                    $tax_return->interest = $second_last_debt_penalty->rate_amount;
                    $tax_return->outstanding_amount = 0;
                    $tax_return->total_amount = $second_last_debt_penalty->penalty_amount;
                    $tax_return->curr_payment_due_date = $second_last_debt_penalty->end_date;
                    $tax_return->payment_status = ReturnStatus::COMPLETE;
                    $tax_return->return->status = ReturnStatus::COMPLETE;
                    $tax_return->return->save();
                    $tax_return->save();
                } else if (count($tax_return->penalties) == 2) {
                    $second_last_debt_penalty = $tax_return->penalties->firstOrFail();
                    $tax_return->penalty = floatval($second_last_debt_penalty->penalty_amount) - floatval($second_last_debt_penalty->rate_amount);
                    $tax_return->interest = $second_last_debt_penalty->rate_amount;
                    $tax_return->outstanding_amount = 0;
                    $tax_return->total_amount = $second_last_debt_penalty->penalty_amount;
                    $tax_return->curr_payment_due_date = $second_last_debt_penalty->end_date;
                    $tax_return->payment_status = ReturnStatus::COMPLETE;
                    $tax_return->return->status = ReturnStatus::COMPLETE;
                    $tax_return->return->save();
                    $tax_return->save();
                } else if (count($tax_return->penalties) == 1) {
                    $tax_return->penalty = $tax_return->return->penalty;
                    $tax_return->interest = $tax_return->return->interest;
                    $tax_return->outstanding_amount = 0;
                    $tax_return->total_amount = $tax_return->return->total_amount_due_with_penalties;
                    $tax_return->curr_payment_due_date = $tax_return->return->payment_due_date;
                    $tax_return->payment_status = ReturnStatus::COMPLETE;
                    $tax_return->return->status = ReturnStatus::COMPLETE;
                    $tax_return->return->save();
                    $tax_return->save();
                }

                $this->sign($tax_return);

                // If return has one debt penalty soft delete it and update the tax_return with original return figure, mark it as paid, update main tax return status to paid
                $rollback = DebtRollback::create([
                    'debt_id' => $tax_return->id,
                    'debt_type' => TaxReturn::class,
                    'rolled_from_debt_penalty_id' => $last_debt_penalty->id,
                    'rolled_to_debt_penalty_id' => $second_last_debt_penalty->id ?? null,
                    'penalty' => floatval($last_debt_penalty->penalty_amount) - floatval($last_debt_penalty->rate_amount),
                    'interest' => $last_debt_penalty->rate_amount,
                    'outstanding_amount' => $last_debt_penalty->penalty_amount,
                    'rolled_by' => Auth::id(),
                    'rolled_at' => Carbon::now()->toDateTimeString(),
                ]);

                if ($latestBill) {
                    CancelBill::dispatch($latestBill, 'Previous bill has already been paid');
                    $latestBill->delete();
                }

                if ($last_debt_penalty) {
                    $last_debt_penalty->delete();
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                throw new Exception('Something went wrong');
            }
        } else {
            throw new Exception('The previous bill has not been paid, Please verify if it is paid');
        }
    }

    public function rollBackLatestAssessmentDebtPenalty($assessment)
    {
        // Get the sencond latest bill
        $secondLatestBill = $assessment->secondLatest->skip(1)->first();

        // Check if the bill has been paid in zm_bill & *double check from reconcilliations table
        if ($secondLatestBill == null) {
            throw new Exception('The previous bill does not exist');
        }

        if ($secondLatestBill->status == 'paid') {
            // Take the last debt penalty and soft delete it
            $last_debt_penalty = $assessment->penalties->last();

            // Cancel the bill which had incremented penalty (last bill)
            $latestBill = $assessment->latestBill;

            DB::beginTransaction();
            try {
                // Get the second last debt penalty, if we have two penalties get the first one
                if (count($assessment->penalties) > 2) {
                    $second_last_debt_penalty = $assessment->penalties->skip(1)->firstOrFail();
                    $assessment->penalty_amount = floatval($second_last_debt_penalty->penalty_amount) - floatval($second_last_debt_penalty->rate_amount);
                    $assessment->interest_amount = $second_last_debt_penalty->rate_amount;
                    $assessment->outstanding_amount = 0;
                    $assessment->paid_amount = $second_last_debt_penalty->penalty_amount;
                    $assessment->total_amount = $second_last_debt_penalty->penalty_amount;
                    $assessment->curr_payment_due_date = $second_last_debt_penalty->end_date;
                    $assessment->payment_status = ReturnStatus::COMPLETE;
                    $assessment->save();
                } else if (count($assessment->penalties) == 2) {
                    $second_last_debt_penalty = $assessment->penalties->firstOrFail();
                    $assessment->penalty_amount = floatval($second_last_debt_penalty->penalty_amount) - floatval($second_last_debt_penalty->rate_amount);
                    $assessment->interest_amount = $second_last_debt_penalty->rate_amount;
                    $assessment->outstanding_amount = 0;
                    $assessment->total_amount = $second_last_debt_penalty->penalty_amount;
                    $assessment->curr_payment_due_date = $second_last_debt_penalty->end_date;
                    $assessment->payment_status = ReturnStatus::COMPLETE;
                    $assessment->save();
                } else if (count($assessment->penalties) == 1) {
                    $assessment->penalty_amount = $assessment->original_penalty_amount;
                    $assessment->interest_amount = $assessment->original_interest_amount;
                    $assessment->outstanding_amount = 0;
                    $assessment->total_amount = $assessment->original_total_amount;
                    $assessment->curr_payment_due_date = $assessment->payment_due_date;
                    $assessment->payment_status = ReturnStatus::COMPLETE;
                    $assessment->save();
                }
                // If return has one debt penalty soft delete it and update the assessment with original return figure, mark it as paid, update main tax return status to paid
                $rollback = DebtRollback::create([
                    'debt_id' => $assessment->id,
                    'debt_type' => TaxAssessment::class,
                    'rolled_from_debt_penalty_id' => $last_debt_penalty->id,
                    'rolled_to_debt_penalty_id' => $second_last_debt_penalty->id ?? null,
                    'penalty' => floatval($last_debt_penalty->penalty_amount) - floatval($last_debt_penalty->rate_amount),
                    'interest' => $last_debt_penalty->rate_amount,
                    'outstanding_amount' => $last_debt_penalty->penalty_amount,
                    'rolled_by' => Auth::id(),
                    'rolled_at' => Carbon::now()->toDateTimeString(),
                ]);

                if ($latestBill) {
                    CancelBill::dispatch($latestBill, 'Previous bill has already been paid');
                    $latestBill->delete();
                }

                if ($last_debt_penalty) {
                    $last_debt_penalty->delete();
                }

                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                Log::error($e);
                throw new Exception('Something went wrong');
            }
        } else {
            throw new Exception('The previous bill has not been paid, Please verify if it is paid');
        }
    }
}
