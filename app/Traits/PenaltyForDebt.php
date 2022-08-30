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
        $penaltyEntry = DebtPenalty::where('debt_id', $debtId)->count();

        for ($i = 0; $i < $penaltyIterations; $i++) {

            if ($i == 1) {
                $period = $penaltyEntry + 1;
            }

            if ($i === 0) {
                $penaltableAmount = $taxAmount;
                $latePaymentAmount = $latePaymentBeforeRate * $penaltableAmount;
                $penaltableAmount = $latePaymentAmount + $penaltableAmount;
                $interestAmount = self::calculateInterest($penaltableAmount, $interestRate, $i);
                $penaltableAmount = $interestAmount + $penaltableAmount;

                $penaltableAmountForPerticularMonth = $penaltableAmount;
                dd($penaltableAmountForPerticularMonth);
                $date->addDays(1);
                $fromDate = clone $date;
                $date->addDays(29);
                $toDate = clone $date;

                $checkPenaltyExist = DebtPenalty::where('debt_id', $debtId)
                    ->where('starting_date', $fromDate)
                    ->where('end_date', $toDate)
                    ->exists();

                if (!$checkPenaltyExist) {
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
                }

                continue;
            }

            if ($penaltableAmount == 0) {
                $penaltableAmount = $taxAmount;
            }
            $latePaymentAmount = $latePaymentAfterRate * $penaltableAmount;
            $penaltableAmount = $latePaymentAmount + $penaltableAmount;
            $interestAmount = self::calculateInterest($penaltableAmount, $interestRate, $i);
            $penaltableAmount = $penaltableAmount + $interestAmount;
            $date->addDays(1);
            $fromDate = clone $date;
            $date->addDays(29);
            $toDate = clone $date;

            $checkPenaltyExist = DebtPenalty::where('debt_id', $debtId)
                ->where('starting_date', $fromDate)
                ->where('end_date', $toDate)
                ->exists();

            if (!$checkPenaltyExist) {

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
            }

            $penaltableAmountForPerticularMonth = $penaltableAmount;

            // if ($i == $penaltyIterations - 1) {
            //     $endDate = clone $toDate->addDays(30);
            // }
            $totalAmount = $penaltableAmount;
        }

        $endDate = DebtPenalty::where('debt_id', $debtId)->latest()->first()->end_date;
        return [$endDate, $totalAmount];
    }

    public static function calculateInterest($taxAmount, $rate, $period)
    {
        $interest = $taxAmount * pow(1 + $rate, $period) - $taxAmount;
        return $interest;
    }
}

// <?php

// namespace App\Traits;

// use App\Enum\AppStepStatus;
// use App\Models\Debts\DebtPenalty;
// use App\Models\FinancialYear;
// use App\Models\InterestRate;
// use App\Models\PenaltyRate;

// class PenaltyForDebt
// {
//     public static function getTotalPenalties($debtId, $app_step,  $date, $taxAmount, $penaltyIterations)
//     {

//         $penaltableAmount = 0;

//         $year = FinancialYear::where('code', $date->year)->first();
//         $interestRate = InterestRate::where('year', $year->code)->firstOrFail()->rate;

//         $latePaymentBeforeRate = PenaltyRate::where('financial_year_id', $year->id)
//             ->where('code', 'LPB')
//             ->firstOrFail()->rate;
//         $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $year->id)
//             ->where('code', 'LPA')
//             ->firstOrFail()->rate;

//         $RM20 =  PenaltyRate::where('financial_year_id', $year->id)
//         ->where('code', '20RM')
//         ->firstOrFail()->rate;

//         $RM10 =  PenaltyRate::where('financial_year_id', $year->id)
//         ->where('code', '10RM')
//         ->firstOrFail()->rate;

//         $penaltableAmountForPerticularMonth = 0;

//         $endDate = null;
//         $totalAmount = 0;

//         for ($i = 0; $i < $penaltyIterations; $i++) {
//             if ($i === 0) {
//                 $penaltableAmount = $taxAmount;
//                 $latePaymentAmount = $app_step == AppStepStatus::NORMAL ? $latePaymentBeforeRate * $penaltableAmount : $RM20 * $penaltableAmount;
//                 $penaltableAmount = round($latePaymentAmount + $penaltableAmount, 2);
//                 $interestAmount = $app_step == AppStepStatus::NORMAL ? self::calculateInterest($penaltableAmount, $interestRate, $i) : $RM10;
//                 $penaltableAmount = round($interestAmount + $penaltableAmount, 2);

//                 $penaltableAmountForPerticularMonth = $penaltableAmount;
//                 $date->addDays(1);
//                 $fromDate = clone $date;
//                 $date->addDays(29);
//                 $toDate = clone $date;
//                 DebtPenalty::create([
//                     'debt_id' => $debtId,
//                     'financial_month_name' => $fromDate->day . '-' . $fromDate->monthName . '-' . $fromDate->year . '  to ' . $toDate->day . '-' . $toDate->monthName . '-' . $toDate->year,
//                     'tax_amount' => $taxAmount,
//                     'late_filing' => $lateFilingFee ?? 0,
//                     'late_payment' => $latePaymentAmount ?? 0,
//                     'rate_percentage' => $interestRate,
//                     'rate_amount' => $interestAmount,
//                     'penalty_amount' => $penaltableAmountForPerticularMonth,
//                     'starting_date' => $fromDate,
//                     'end_date' => $toDate,
//                 ]);

//                 continue;
//             }
//             $latePaymentAmount = $app_step == AppStepStatus::NORMAL ? $latePaymentAfterRate * $penaltableAmount : $RM20 * $penaltableAmount;
//             $penaltableAmount = round($latePaymentAmount + $penaltableAmount, 2);
//             $interestAmount = $app_step == AppStepStatus::NORMAL ? self::calculateInterest($penaltableAmount, $interestRate, $i) : $RM10;
//             $penaltableAmount = $penaltableAmount + $interestAmount;
//             $date->addDays(1);
//             $fromDate = clone $date;
//             $date->addDays(29);
//             $toDate = clone $date;

//             DebtPenalty::create([
//                 'debt_id' => $debtId,
//                 'financial_month_name' => $fromDate->day . '-' . $fromDate->monthName . '-' . $fromDate->year . '  to ' . $toDate->day . '-' . $toDate->monthName . '-' . $toDate->year,
//                 'tax_amount' => $penaltableAmountForPerticularMonth,
//                 'late_filing' => 0,
//                 'late_payment' => $latePaymentAmount ?? 0,
//                 'rate_percentage' => $interestRate,
//                 'rate_amount' => $interestAmount,
//                 'penalty_amount' => $penaltableAmount,
//                 'starting_date' => $fromDate,
//                 'end_date' => $toDate,
//             ]);

//             $penaltableAmountForPerticularMonth = $penaltableAmount;

//             if($i == ($penaltyIterations - 1))
//             $endDate =  $toDate->addDays(30);
//             $totalAmount = $penaltableAmount;
//         }

//         return [$endDate, $totalAmount];
//     }

//     public static function calculateInterest($taxAmount, $rate, $period)
//     {
//         $interest = $taxAmount * pow(1 + $rate, $period) - $taxAmount;
//         return $interest;
//     }

// }
