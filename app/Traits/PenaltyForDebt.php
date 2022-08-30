<?php

namespace App\Traits;

use App\Models\Debts\DebtPenalty;
use App\Models\FinancialYear;
use App\Models\InterestRate;
use App\Models\PenaltyRate;

class PenaltyForDebt
{
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

    public static function calculateInterest($taxAmount, $rate, $period)
    {
        $interest = $taxAmount * pow(1 + $rate, $period) - $taxAmount;
        return $interest;
    }
}
