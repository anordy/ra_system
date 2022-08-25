<?php

namespace App\Traits;

use App\Models\Debts\DebtPenalty;
use App\Models\FinancialYear;
use App\Models\InterestRate;
use App\Models\PenaltyRate;

class PenaltyForDebt
{
    public static function getTotalPenalties($debtId, $date, $taxAmount, $penaltyIterations)
    {

        $penaltableAmount = 0;

        $year = FinancialYear::where('code', $date->year)->first();
        $interestRate = InterestRate::where('year', $year->code)->firstOrFail()->rate;
        $latePaymentBeforeRate = PenaltyRate::where('financial_year_id', $year->id)
            ->where('code', 'LPB')
            ->firstOrFail()->rate;
        $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $year->id)
            ->where('code', 'LPA')
            ->firstOrFail()->rate;

        $penaltableAmountForPerticularMonth = 0;

        $endDate = null;
        $totalAmount = 0;

        for ($i = 0; $i < $penaltyIterations; $i++) {
            if ($i === 0) {
                $penaltableAmount = $taxAmount;
                $latePaymentAmount = $latePaymentBeforeRate * $penaltableAmount;
                $penaltableAmount = $latePaymentAmount + $penaltableAmount;
                $interestAmount = self::calculateInterest($penaltableAmount, $interestRate, $i);
                $penaltableAmount = $interestAmount + $penaltableAmount;

                $penaltableAmountForPerticularMonth = $penaltableAmount;
                $date->addDays(1);
                $fromDate = clone $date;
                $date->addDays(29);
                $toDate = clone $date;
                DebtPenalty::create([
                    'debt_id' => $debtId,
                    'financial_month_name' => $fromDate->day . '-' . $fromDate->monthName . '-' . $fromDate->year . '  to ' . $toDate->day . '-' . $toDate->monthName . '-' . $toDate->year,
                    'tax_amount' => $taxAmount,
                    'late_filing' => $lateFilingFee ?? 0,
                    'late_payment' => $latePaymentAmount ?? 0,
                    'rate_percentage' => $interestRate,
                    'rate_amount' => $interestAmount,
                    'penalty_amount' => $penaltableAmountForPerticularMonth,
                    'starting_date' => $fromDate,
                    'end_date' => $toDate,
                ]);

                continue;
            }

            $latePaymentAmount = $latePaymentAfterRate * $penaltableAmount;
            $penaltableAmount = $latePaymentAmount + $penaltableAmount;
            $interestAmount = self::calculateInterest($penaltableAmount, $interestRate, $i);
            $penaltableAmount = $penaltableAmount + $interestAmount;
            $date->addDays(1);
            $fromDate = clone $date;
            $date->addDays(29);
            $toDate = clone $date;

            DebtPenalty::create([
                'debt_id' => $debtId,
                'financial_month_name' => $fromDate->day . '-' . $fromDate->monthName . '-' . $fromDate->year . '  to ' . $toDate->day . '-' . $toDate->monthName . '-' . $toDate->year,
                'tax_amount' => $penaltableAmountForPerticularMonth,
                'late_filing' => 0,
                'late_payment' => $latePaymentAmount ?? 0,
                'rate_percentage' => $interestRate,
                'rate_amount' => $interestAmount,
                'penalty_amount' => $penaltableAmount,
                'starting_date' => $fromDate,
                'end_date' => $toDate,
            ]);

            $penaltableAmountForPerticularMonth = $penaltableAmount;
            

            if($i == ($penaltyIterations - 1))
            $endDate =  $toDate->addDays(30);
            $totalAmount = $penaltableAmount;
        }

        return [$endDate, $totalAmount];
    }

    public static function calculateInterest($taxAmount, $rate, $period)
    {
        $interest = $taxAmount * pow(1 + $rate, $period) - $taxAmount;
        return $interest;
    }

}
