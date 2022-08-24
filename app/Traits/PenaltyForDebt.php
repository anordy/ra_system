<?php

namespace App\Traits;

use App\Models\BusinessLocation;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\InterestRate;
use App\Models\PenaltyRate;
use App\Models\Returns\Petroleum\QuantityCertificate;
use Carbon\Carbon;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenaltyForDebt
{
    public static function getTotalPenalties($financialMonth, $due_date, $taxAmount){
        $paymentStructure = [];

        $penaltableAmount = 0;
        $date = self::getDateFromFinancialMonth($financialMonth, $due_date->day);

        $diffInMonths = $date->diffInMonths(Carbon::now());

        $interestRate = InterestRate::where('year', $financialMonth->year->code)->firstOrFail()->rate;
        $latePaymentBeforeRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'LPB')->firstOrFail()->rate;
        $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'LPA')->firstOrFail()->rate;

        $paymentStructure = [];
        $penaltableAmountForPerticularMonth = 0;
        for ($i = 0; $i < $diffInMonths; $i++) {
            if($i === 0){

                $penaltableAmount = $taxAmount;
                $latePaymentAmount = ($latePaymentBeforeRate * $penaltableAmount);
                $penaltableAmount = $latePaymentAmount + $penaltableAmount;
                $interestAmount = self::calculateInterest($penaltableAmount, $interestRate, $i);
                $penaltableAmount = $interestAmount + $penaltableAmount;

                $penaltableAmountForPerticularMonth = $penaltableAmount;
                $date->addDays(1);
                $fromDate = clone $date;
                $date->addDays(29);
                $toDate = clone $date;
                $paymentStructure[] = [
                    'returnMonth' => $fromDate->day .'-'. $fromDate->monthName .'-'. $fromDate->year .'  to '. $toDate->day .'-'. $toDate->monthName .'-'. $toDate->year,
                    'taxAmount' => $taxAmount,
                    'lateFilingAmount' => $lateFilingFee ?? 0,
                    'latePaymentAmount' => $latePaymentAmount ?? 0,
                    'interestRate' => $interestRate,
                    'interestAmount' => $interestAmount,
                    'penaltyAmount' => $penaltableAmountForPerticularMonth,
                ];
                
                
                continue;
            }
            
            $latePaymentAmount = ($latePaymentAfterRate * $penaltableAmount);
            $penaltableAmount = $latePaymentAmount + $penaltableAmount;
            $interestAmount = self::calculateInterest($penaltableAmount, $interestRate, $i);
            $penaltableAmount = $penaltableAmount + $interestAmount;
            $date->addDays(1);
            $fromDate = clone $date;
            $date->addDays(29);
            $toDate = clone $date;
            $paymentStructure[] = [
                'returnMonth' => $fromDate->day .'-'. $fromDate->monthName .'-'. $fromDate->year .'  to  '. $toDate->day .'-'. $toDate->monthName .'-'. $toDate->year,
                'taxAmount' => $penaltableAmountForPerticularMonth,
                'lateFilingAmount' =>  0,
                'latePaymentAmount' => $latePaymentAmount ?? 0,
                'interestRate' => $interestRate,
                'interestAmount' => $interestAmount,
                'penaltyAmount' => $penaltableAmount,
            ];

            $penaltableAmountForPerticularMonth = $penaltableAmount;
        }
        
        return $paymentStructure;
    }

    public static function getDateFromFinancialMonth($financialMonth, $ascertainedDate){

        return Carbon::create($financialMonth->year->code, $financialMonth->number, $ascertainedDate);
    }

    public static function calculateInterest($taxAmount, $rate, $period){
        $interest = ($taxAmount * pow((1 + $rate), $period)) - $taxAmount;
        return $interest;
    }

}

