<?php

namespace App\Traits;

use Exception;
use Carbon\Carbon;
use App\Models\PenaltyRate;
use App\Models\InterestRate;
use App\Models\FinancialYear;
use App\Models\Debts\DebtPenalty;
use App\Models\Debts\DebtScheduleState;
use App\Models\Returns\TaxReturn;
use Illuminate\Support\Facades\DB;
use App\Models\TaxAssessments\TaxAssessment;

class PenaltyForDebt
{
    use ExchangeRateTrait;

    public static function getTotalPenalties($debtId, $date, $taxAmount, $period)
    {
        $penaltableAmount = 0;

        $year = FinancialYear::where('code', $date->year)->first();
        $interestRate = InterestRate::where('year', $year->code)->firstOrFail()->rate;
        $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $year->id)
            ->where('code', 'LPA')
            ->firstOrFail()->rate;

        $endDate = null;
        $totalAmount = 0;

        $penaltableAmount = $taxAmount;
        $latePaymentAmount = $latePaymentAfterRate * $penaltableAmount;
        $penaltableAmount = $latePaymentAmount + $penaltableAmount;
        $interestAmount = self::calculateInterest($penaltableAmount, $interestRate, $period);
        $penaltableAmount = $penaltableAmount + $interestAmount;
        $date->addDays(1);
        $fromDate = clone $date;
        $date->addDays(29);
        $toDate = clone $date;

        DebtPenalty::create([
            'debt_id' => $debtId,
            'financial_month_name' => $fromDate->day . '-' . $fromDate->monthName . '-' . $fromDate->year . '  to ' . $toDate->day . '-' . $toDate->monthName . '-' . $toDate->year,
            'tax_amount' => $taxAmount,
            'late_filing' => 0,
            'late_payment' => $latePaymentAmount ?? 0,
            'rate_percentage' => $interestRate,
            'rate_amount' => $interestAmount,
            'penalty_amount' => $penaltableAmount,
            'start_date' => $fromDate,
            'end_date' => $toDate,
        ]);

        $totalAmount = $penaltableAmount;

        $endDate = DebtPenalty::where('debt_id', $debtId)
            ->latest()
            ->first()->end_date;

        return [$endDate, $totalAmount];
    }
    

    /**
     * Generate returns penalty
     */
    public static function generateReturnsPenalty($tax_return)
    {
        $outstanding_amount = $tax_return->outstanding_amount;

        $curr_payment_due_date = Carbon::create($tax_return->curr_payment_due_date);

        $year = FinancialYear::where('code', $curr_payment_due_date->year)->first();

        if (!$year) {
            throw new \Exception("JOB FAILED TO RUN, NO FINANCIAL YEAR {$curr_payment_due_date->year} DATA");
        }

        $interestRate = InterestRate::where('year', $year->code)->first();

        if (!$interestRate) {
            throw new \Exception("JOB FAILED TO RUN, NO INTEREST RATE FOR THE YEAR {$curr_payment_due_date->year}");
        }

        $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $year->id)
            ->where('code', 'LPA')
            ->first();

        if (!$latePaymentAfterRate) {
            throw new \Exception("JOB FAILED TO RUN, NO LATE PAYMENT RATE FOR THE YEAR {$curr_payment_due_date->year}");
        }
        
        $period = $tax_return->periods + $tax_return->penatableMonths;

        $penaltableAmount = $outstanding_amount;
        $latePaymentAmount = $latePaymentAfterRate->rate * $penaltableAmount;
        $penaltableAmount = $latePaymentAmount + $penaltableAmount;
        $interestAmount = self::calculateInterest($penaltableAmount, $interestRate->rate, $period);
        $penaltableAmount = $penaltableAmount + $interestAmount;

        $start_date = Carbon::create($tax_return->curr_payment_due_date)->addDay()->startOfDay();
        $end_date = Carbon::create($tax_return->curr_payment_due_date)->addDays(30)->endOfDay();

        try {
            $previous_debt_penalty_id = $tax_return->latestPenalty->id ?? null;

            $debtPenalty = DebtPenalty::create([
                'debt_id' => $tax_return->id,
                'debt_type' => TaxReturn::class,
                'financial_month_name' => $start_date->day . '-' . $start_date->monthName . '-' . $start_date->year . '  to ' . $end_date->day . '-' . $end_date->monthName . '-' . $end_date->year,
                'tax_amount' => $outstanding_amount,
                'late_filing' => 0,
                'late_payment' => $latePaymentAmount ?? 0,
                'rate_percentage' => $interestRate->rate,
                'rate_amount' => $interestAmount,
                'penalty_amount' => $penaltableAmount,
                'start_date' => $start_date->startOfDay(),
                'end_date' => $end_date,
                'currency' => $tax_return->currency,
                'currency_rate_in_tz' => ExchangeRateTrait::getExchangeRate($tax_return->currency)
            ]);
    
            $tax_return->penalty = $tax_return->penalty + $tax_return->penalties->sum('late_payment');
            $tax_return->interest = $tax_return->interest + $tax_return->penalties->sum('rate_amount');
            $tax_return->curr_payment_due_date = $end_date;
            $tax_return->total_amount = round($penaltableAmount, 2);
            $tax_return->outstanding_amount = round($penaltableAmount, 2);
            $tax_return->save();

            self::recordReturnDebtState($tax_return, $previous_debt_penalty_id, $debtPenalty->id);

        } catch(Exception $e) {
            throw $e;
        }

    }

    /**
     * Generate debt assessment penalty
     */
    public static function generateAssessmentsPenalty($assessment)
    {
        $outstanding_amount = $assessment->outstanding_amount;

        $curr_payment_due_date = Carbon::create($assessment->curr_payment_due_date);

        $year = FinancialYear::where('code', $curr_payment_due_date->year)->first();

        if (!$year) {
            throw new \Exception("JOB FAILED TO RUN, NO FINANCIAL YEAR {$curr_payment_due_date->year} DATA");
        }

        $interestRate = InterestRate::where('year', $year->code)->first();

        if (!$interestRate) {
            throw new \Exception("JOB FAILED TO RUN, NO INTEREST RATE FOR THE YEAR {$curr_payment_due_date->year}");
        }

        $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $year->id)
            ->where('code', 'LPA')
            ->first();

        if (!$latePaymentAfterRate) {
            throw new \Exception("JOB FAILED TO RUN, NO LATE PAYMENT RATE FOR THE YEAR {$curr_payment_due_date->year}");
        }
        
        $period = $assessment->periods + $assessment->penatableMonths;

        $penaltableAmount = $outstanding_amount;
        $latePaymentAmount = $latePaymentAfterRate->rate * $penaltableAmount;
        $penaltableAmount = $latePaymentAmount + $penaltableAmount;
        $interestAmount = self::calculateInterest($penaltableAmount, $interestRate->rate, $period);
        $penaltableAmount = $penaltableAmount + $interestAmount;

        $start_date = Carbon::create($assessment->curr_payment_due_date)->addDay()->startOfDay();
        $end_date = Carbon::create($assessment->curr_payment_due_date)->addDays(30)->endOfDay();

        $previous_debt_penalty_id = $assessment->latestPenalty->id ?? null;

        try {
            $debtPenalty = DebtPenalty::create([
                'debt_id' => $assessment->id,
                'debt_type' => TaxAssessment::class,
                'financial_month_name' => $start_date->day . '-' . $start_date->monthName . '-' . $start_date->year . '  to ' . $end_date->day . '-' . $end_date->monthName . '-' . $end_date->year,
                'tax_amount' => $outstanding_amount,
                'late_filing' => 0,
                'late_payment' => $latePaymentAmount ?? 0,
                'rate_percentage' => $interestRate->rate,
                'rate_amount' => $interestAmount,
                'penalty_amount' => $penaltableAmount,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'currency' => $assessment->currency,
                'currency_rate_in_tz' => ExchangeRateTrait::getExchangeRate($assessment->currency)
            ]);
    
            $assessment->penalty_amount = $assessment->penalty_amount + $assessment->penalties->sum('late_payment');
            $assessment->interest_amount = $assessment->interest_amount + $assessment->penalties->sum('rate_amount');
            $assessment->curr_payment_due_date = $end_date;
            $assessment->total_amount = round($penaltableAmount, 2);
            $assessment->outstanding_amount = round($penaltableAmount, 2);
            $assessment->save();
            self::recordAssessmentDebtState($assessment, $previous_debt_penalty_id, $debtPenalty->id);
        } catch (Exception $e) {
            throw $e;
        }

    }

    public static function calculateInterest($taxAmount, $rate, $period)
    {
        $interest = $taxAmount * pow(1 + $rate, $period) - $taxAmount;
        return $interest;
    }

    /**
     * Record tax return debt state
     */
    public static function recordReturnDebtState($tax_return, $previous_debt_penalty_id, $current_debt_penalty_id) {
        try {
            DebtScheduleState::create([
                'penalty' => $tax_return->penalty,
                'interest' => $tax_return->interest,
                'total_amount' => $tax_return->total_amount,
                'outstanding_amount' => $tax_return->outstanding_amount,
                'previous_debt_penalty_id' => $previous_debt_penalty_id,
                'current_debt_penalty_id' => $current_debt_penalty_id,
                'debt_type' => TaxReturn::class,
                'debt_id' => $tax_return->id,
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Record tax assessment debt state 
     */
    public static function recordAssessmentDebtState($assessment, $previous_debt_penalty_id, $current_debt_penalty_id) {
        try {
            DebtScheduleState::create([
                'penalty' => $assessment->penalty_amount,
                'interest' => $assessment->interest_amount,
                'total_amount' => $assessment->total_amount,
                'outstanding_amount' => $assessment->outstanding_amount,
                'previous_debt_penalty_id' => $previous_debt_penalty_id,
                'current_debt_penalty_id' => $current_debt_penalty_id,
                'debt_type' => TaxAssessment::class,
                'debt_id' => $assessment->id,
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
