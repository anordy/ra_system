<?php

namespace App\Traits;

use App\Models\Debts\DebtPenalty;
use App\Models\ExchangeRate;
use App\Models\FinancialYear;
use App\Models\InterestRate;
use App\Models\PenaltyRate;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAssessments\TaxAssessment;
use Carbon\Carbon;

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
     * Generate first penalty for debt which never had any penalty
     */
    public static function generateReturnsPenalty($tax_return)
    {
        $outstanding_amount = $tax_return->outstanding_amount;

        $curr_payment_due_date = Carbon::create($tax_return->curr_payment_due_date);

        $year = FinancialYear::where('code', $curr_payment_due_date->year)->firstOrFail();

        $interestRate = InterestRate::where('year', $year->code)->firstOrFail()->rate;

        $latePaymentBeforeRate = PenaltyRate::where('financial_year_id', $year->id)
            ->where('code', 'LPA')
            ->firstOrFail()->rate;
        
        $period = $tax_return->periods + $tax_return->penatableMonths;

        $penaltableAmount = $outstanding_amount;
        $latePaymentAmount = $latePaymentBeforeRate * $penaltableAmount;
        $penaltableAmount = $latePaymentAmount + $penaltableAmount;
        $interestAmount = self::calculateInterest($penaltableAmount, $interestRate, $period);
        $penaltableAmount = $penaltableAmount + $interestAmount;

        $start_date = Carbon::create($tax_return->curr_payment_due_date)->addDay();
        $end_date = Carbon::create($tax_return->curr_payment_due_date)->addDays(30);

        DebtPenalty::create([
            'debt_id' => $tax_return->id,
            'debt_type' => TaxReturn::class,
            'financial_month_name' => $start_date->day . '-' . $start_date->monthName . '-' . $start_date->year . '  to ' . $end_date->day . '-' . $end_date->monthName . '-' . $end_date->year,
            'tax_amount' => $outstanding_amount,
            'late_filing' => 0,
            'late_payment' => $latePaymentAmount ?? 0,
            'rate_percentage' => $interestRate,
            'rate_amount' => $interestAmount,
            'penalty_amount' => $penaltableAmount,
            'start_date' => $start_date,
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
    }

    /**
     * Generate first penalty for debt which never had any penalty
     */
    public static function generateAssessmentsPenalty($tax_return)
    {
        $outstanding_amount = $tax_return->outstanding_amount;

        $curr_payment_due_date = Carbon::create($tax_return->curr_payment_due_date);

        $year = FinancialYear::where('code', $curr_payment_due_date->year)->firstOrFail();

        $interestRate = InterestRate::where('year', $year->code)->firstOrFail()->rate;

        $latePaymentBeforeRate = PenaltyRate::where('financial_year_id', $year->id)
            ->where('code', 'LPA')
            ->firstOrFail()->rate;
        
        $period = $tax_return->periods + $tax_return->penatableMonths;

        $penaltableAmount = $outstanding_amount;
        $latePaymentAmount = $latePaymentBeforeRate * $penaltableAmount;
        $penaltableAmount = $latePaymentAmount + $penaltableAmount;
        $interestAmount = self::calculateInterest($penaltableAmount, $interestRate, $period);
        $penaltableAmount = $penaltableAmount + $interestAmount;

        $start_date = Carbon::create($tax_return->curr_payment_due_date)->addDay();
        $end_date = Carbon::create($tax_return->curr_payment_due_date)->addDays(30);

        DebtPenalty::create([
            'debt_id' => $tax_return->id,
            'debt_type' => TaxAssessment::class,
            'financial_month_name' => $start_date->day . '-' . $start_date->monthName . '-' . $start_date->year . '  to ' . $end_date->day . '-' . $end_date->monthName . '-' . $end_date->year,
            'tax_amount' => $outstanding_amount,
            'late_filing' => 0,
            'late_payment' => $latePaymentAmount ?? 0,
            'rate_percentage' => $interestRate,
            'rate_amount' => $interestAmount,
            'penalty_amount' => $penaltableAmount,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'currency' => $tax_return->currency,
            'currency_rate_in_tz' => ExchangeRateTrait::getExchangeRate($tax_return->currency)
        ]);

        $tax_return->penalty_amount = $tax_return->penalty_amount + $tax_return->penalties->sum('late_payment');
        $tax_return->interest_amount = $tax_return->interest_amount + $tax_return->penalties->sum('rate_amount');
        $tax_return->curr_payment_due_date = $end_date;
        $tax_return->total_amount = round($penaltableAmount, 2);
        $tax_return->outstanding_amount = round($penaltableAmount, 2);
        $tax_return->save();
    }

    public static function calculateInterest($taxAmount, $rate, $period)
    {
        $interest = $taxAmount * pow(1 + $rate, $period) - $taxAmount;
        return $interest;
    }
}
