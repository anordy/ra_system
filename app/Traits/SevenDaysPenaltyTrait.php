<?php

namespace App\Traits;

use App\Models\BusinessLocation;
use App\Models\FinancialYear;
use App\Models\PenaltyRate;
use App\Models\SevenDaysFinancialMonth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait SevenDaysPenaltyTrait
{

    use ExchangeRateTrait;

    public static function getTotalPenaltiesSevenDays($financialMonth, $taxAmount, $taxTypeCurency)
    {
        try {
            $date = self::getDateFromFinancialMonth($financialMonth);

            $diffInMonths = $date->diffInMonths(Carbon::now());

            $paymentStructure = [];
            $penaltyRate = PenaltyRate::select('rate')->where('financial_year_id', $financialMonth->year->id)->where('code', 'PFMobilesTrans')->firstOrFail()->rate;
            $penaltableAMountForPerticularMonth = $penaltyRate;
            for ($i = 0; $i < $diffInMonths; $i++) {
                if ($i === 0) {
                    $penaltableAmount = $penaltableAMountForPerticularMonth + $taxAmount;
                    $paymentStructure[] = [
                        'returnMonth' => $date->monthName,
                        'taxAmount' => $taxAmount,
                        'penaltyAmount' => $penaltableAmount,
                        'lateFilingAmount' => 0,
                        'latePaymentAmount' => $penaltyRate,
                        'interestRate' => 0,
                        'interestAmount' => 0
                    ];
                    $penaltableAMountForPerticularMonth = $penaltableAmount;
                    $date->addMonth();
                }
                $penaltableAmount = $penaltableAMountForPerticularMonth + $penaltyRate;

                $paymentStructure[] = [
                    'returnMonth' => $date->monthName,
                    'taxAmount' => $penaltableAMountForPerticularMonth,
                    'penaltyAmount' => $penaltableAmount,
                    'lateFilingAmount' => 0,
                    'latePaymentAmount' => $penaltyRate,
                    'interestRate' => 0,
                    'interestAmount' => 0
                ];
                $penaltableAMountForPerticularMonth = $penaltableAmount;
                $date->addMonth();
            }

            return $paymentStructure;

        } catch (\Exception $exception) {
            Log::error('TRAITS-SEVEN-DAYS-PENALTY-TRAIT-GET-TOTAL-PENALTIES-SEVEN-DAYS', [$exception]);
            throw $exception;
        }
    }

    public static function getTotalsSevenDays($financialMonth, $taxAmount, $taxTypeCurency)
    {
        try {
            $penalties = self::getTotalPenaltiesSevenDays($financialMonth, $taxAmount, $taxTypeCurency);

            $totalLateFillingPenalty = 0;
            $totalLatePaymentPenalty = 0;
            $totalInterest = 0;
            foreach ($penalties as $key => $penalty) {
                $totalLateFillingPenalty += $penalty['lateFilingAmount'];
                $totalLatePaymentPenalty += $penalty['latePaymentAmount'];
                $totalInterest += $penalty['interestAmount'];
            }
            $totalPenalty = ($totalLateFillingPenalty + $totalLatePaymentPenalty);
            return [
                'basicTaxAMount' => $taxAmount,
                'penalty' => $totalPenalty,
                'interest' => $totalInterest,
            ];

        } catch (\Exception $exception) {
            Log::error('TRAITS-SEVEN-DAYS-PENALTY-TRAIT-GET-TOTAL-SEVEN-DAYS', [$exception]);
            throw $exception;
        }
    }

    public static function isLateFiling($financialMonth)
    {
        // We have filing month
        if (Carbon::now()->greaterThan($financialMonth->due_date)) {
            return true;
        }

        return false;
    }

    public static function getLateFilingFee($financialMonth, $taxAmount, $taxTypeCurency)
    {
        try {
            $lateFilingRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'LF')->firstOrFail()->rate;
            $percentageFee = $lateFilingRate * $taxAmount;
            $weGRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'WEG')->firstOrFail()->rate;

            $rate = 1;
            if ($taxTypeCurency !== 'TZS') {
                $rate = self::getExchangeRate($taxTypeCurency);
                $percentageFee = ($percentageFee * $rate);
            }

            if ($percentageFee >= $weGRate) {

                return ($percentageFee / $rate);
            }

            if ($taxTypeCurency !== 'TZS') {
                $weGRate = ($weGRate / $rate);
            }

            return $weGRate;

        } catch (\Exception $exception) {
            Log::error('TRAITS-SEVEN-DAYS-PENALTY-TRAIT-GET-LATE-FILING', [$exception]);
            throw $exception;
        }
    }

    public static function getFilingMonthSevenDays($locationId, $ReturnClass)
    {
        try {
            // Check last return w/ Status
            if ($return = $ReturnClass::where('business_location_id', $locationId)->latest()->first()) {
                if ($return->status === 'complete') {
                    return self::checkNextViableReturnMonth($return->financialMonth);
                } else {
                    return $return->financialMonth;
                }
            }

            // If not, Check date of commence
            $date = BusinessLocation::findOrFail($locationId, ['effective_date'])->business->effective_date;

            $financialYear = FinancialYear::where('code', $date->year)->firstOrFail();
            $financialMonth = SevenDaysFinancialMonth::where('financial_year_id', $financialYear->id)
                ->where('number', $date->month)
                ->firstOrFail();

            return self::checkNextViableReturnMonth($financialMonth);

        } catch (\Exception $exception) {
            Log::error('TRAITS-SEVEN-DAYS-PENALTY-TRAIT-GET-FILING-MONTH-SEVEN-DAYS', [$exception]);
            throw $exception;
        }

    }

    public static function checkNextViableReturnMonth($financialMonth)
    {
        try {
            // Get next return month,
            // check if 12, add year, get first month
            if ($financialMonth->number === 12) {
                $year = FinancialYear::where('code', $financialMonth->year->code + 1);
                $month = SevenDaysFinancialMonth::where('number', 1)
                    ->where('financial_year_id', $year->id)
                    ->firstOrFail();
            } else {
                $month = SevenDaysFinancialMonth::where('financial_year_id', $financialMonth->financial_year_id)
                    ->where('number', $financialMonth->number + 1)
                    ->firstOrFail();
            }

            $date = self::getDateFromFinancialMonth($month);

            // Compare with current date
            if ($date->lessThanOrEqualTo(Carbon::now())) {
                return $month;
            } else {
                return false;
            }
        } catch (\Exception $exception) {
            Log::error('TRAITS-SEVEN-DAYS-PENALTY-TRAIT-CHECK-NEXT-VIABLE-RETURN-MONTH', [$exception]);
            throw $exception;
        }
    }

    public static function getDateFromFinancialMonth($financialMonth)
    {
        return Carbon::create($financialMonth->year->code, $financialMonth->number, 1);
    }

    public static function calculateInterest($taxAmount, $rate, $period)
    {
        return ($taxAmount * pow((1 + $rate), $period)) - $taxAmount;
    }
}

