<?php

namespace App\Traits;

use App\Models\BusinessLocation;
use App\Models\SevenDaysFinancialMonth;
use App\Models\FinancialYear;
use App\Models\InterestRate;
use App\Models\PenaltyRate;
use Carbon\Carbon;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Support\Facades\Log;

trait SevenDaysPenaltyTrait
{

    public static function getTotalPenaltiesSevenDays($financialMonth, $taxAmount, $taxTypeCurency){
        $lateFilingFee = 0;

        if(self::isLateFiling($financialMonth)){
            $lateFilingFee = self::getLateFilingFee($financialMonth, $taxAmount, $taxTypeCurency);
        }

        // Get late payments penalties
        $penaltableAmount = 0;

        $date = self::getDateFromFinancialMonth($financialMonth);

        $diffInMonths = $date->diffInMonths(Carbon::now());

        $paymentStructure = [];
        $penaltyRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'PFMobilesTrans')->firstOrFail()->rate;
        $penaltableAMountForPerticularMonth = $penaltyRate;
        for ($i = 0; $i < $diffInMonths; $i++) {
            if($i === 0){
                $penaltableAmount = $penaltableAMountForPerticularMonth + $taxAmount;
                $paymentStructure[] = [
                    'returnMonth' => $date->monthName,
                    'taxAmount' => $taxAmount,
                    'penaltyAmount' => $penaltableAmount,
                    'lateFilingAmount' => 0,
                    'latePaymentAmount' =>  $penaltyRate,
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
                'latePaymentAmount' =>  $penaltyRate,
                'interestRate' => 0,
                'interestAmount' => 0
            ];
            $penaltableAMountForPerticularMonth = $penaltableAmount;
            $date->addMonth();
        }

        return $paymentStructure;
    }

    public static function getTotalsSevenDays($financialMonth, $taxAmount, $taxTypeCurency){
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
    }

    public static function isLateFiling($financialMonth){
        // We have filing month
        if(Carbon::now()->greaterThan($financialMonth->due_date)){
            return true;
        }

        return false;
    }

    public static function getLateFilingFee($financialMonth, $taxAmount, $taxTypeCurency){
        $lateFilingRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'LF')->firstOrFail()->rate;
        $percentageFee = $lateFilingRate * $taxAmount;
        $weGRate = $lateFilingRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'WEG')->firstOrFail()->rate;

        $rate = 1;
        if($taxTypeCurency !== 'TZS') {
            $rate = 2300;
            $percentageFee = self::checkCurrency($percentageFee, $rate);
        }

        if($percentageFee >= $weGRate){

            return ($percentageFee / $rate);
        }

        if($taxTypeCurency !== 'TZS') {
            $weGRate = ($weGRate / $rate);
        }

        return $weGRate;
    }

    private function checkCurrency($percentageFee, $rate) {
        // Api from BOT
        // USD or any from BOT

        $percentageFee = ($percentageFee * $rate);

        return $percentageFee;
    }

    public static function getFilingMonthSevenDays($locationId, $ReturnClass){

        // Check last return w/ Status
        if($return = $ReturnClass::where('business_location_id', $locationId)->latest()->first()){
            if($return->status === 'complete'){

                return self::checkNextViableReturnMonth($return->financialMonth);
            } else {
                return $return->financialMonth;
            }
        }

        // If not, Check date of commence
        $date = BusinessLocation::find($locationId)->business->date_of_commencing;

        $financialYear = FinancialYear::where('code', $date->year)->first();
        $financialMonth = SevenDaysFinancialMonth::where('financial_year_id', $financialYear->id)
            ->where('number', $date->month)
            ->first();

        return self::checkNextViableReturnMonth($financialMonth);

    }

    public static function checkNextViableReturnMonth($financialMonth){
        // Get next return month,
        // check if 12, add year, get first month
        if($financialMonth->number === 12){
            $year = FinancialYear::where('code', $financialMonth->year->code + 1);
            // TODO: First or fail
            $month = SevenDaysFinancialMonth::where('number', 1)
                ->where('financial_year_id', $year->id)
                ->first();
        } else {
            // TODO: First or fail
            $month = SevenDaysFinancialMonth::where('financial_year_id', $financialMonth->financial_year_id)
                ->where('number', $financialMonth->number + 1)
                ->first();
        }

        $date = self::getDateFromFinancialMonth($month);

        // Compare with current date
        if($date->lessThanOrEqualTo(Carbon::now())){
            return $month;
        } else {
            return false;
        }
    }

    public static function getDateFromFinancialMonth($financialMonth){
        return Carbon::create($financialMonth->year->code, $financialMonth->number, 1);
    }

    public static function calculateInterest($taxAmount, $rate, $period){
        $interest = ($taxAmount * pow((1 + $rate), $period)) - $taxAmount;
        return $interest;
    }
}

