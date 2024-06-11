<?php

namespace App\Traits;

use App\Models\BusinessLocation;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\InterestRate;
use App\Models\PenaltyRate;
use Carbon\Carbon;

trait PenaltyTrait
{

    use ExchangeRateTrait;


    public function getTotalPenalties($financialMonth, $taxAmount, $taxTypeCurency){
        $lateFilingFee = 0; // 100,000
        if($this->isLateFiling($financialMonth)){
            $lateFilingFee = $this->getLateFilingFee($financialMonth, $taxAmount, $taxTypeCurency);
        }

        // Get late total payments
        $penaltableAmount = 0;

        $date = $this->getDateFromFinancialMonth($financialMonth);
        $diffInMonths = $date->diffInMonths(Carbon::now());// 2
        $interestRate = InterestRate::where('year', $financialMonth->year->code)->firstOrFail()->rate; // 0.018
        $latePaymentBeforeRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'LPB')->firstOrFail()->rate; // 0.2
        $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'LPA')->firstOrFail()->rate; // 0.1

        $paymentStructure = [];
        $penaltableAMountForPerticularMonth = 0;
        for ($i = 0; $i < $diffInMonths; $i++) {
            if($i === 0){

                $penaltableAmount = $lateFilingFee + $taxAmount;// 100,000
                $latePaymentAmount = ($latePaymentBeforeRate * $penaltableAmount); // 100,000 * 0.2 = 20,000
                $penaltableAmount = $latePaymentAmount + $penaltableAmount; // 120,000
                $interestAmount = $this->calculateInterest($penaltableAmount, $interestRate, $i); // 0
                $penaltableAmount = $interestAmount + $penaltableAmount; // 120,000

                $penaltableAMountForPerticularMonth = $penaltableAmount; // 120,000

                $paymentStructure[] = [
                    'returnMonth' => $date->monthName,
                    'taxAmount' => $taxAmount,
                    'penaltyAmount' => $penaltableAMountForPerticularMonth,
                    'lateFilingAmount' => $lateFilingFee ?? 0,
                    'latePaymentAmount' => 0,
                    'interestRate' => $interestRate,
                    'interestAmount' => $interestAmount
                ];

                $date->addMonth();
                continue;
            }

            $latePaymentAmount = ($latePaymentAfterRate * $penaltableAmount);
            $penaltableAmount = $latePaymentAmount + $penaltableAmount;
            $interestAmount = $this->calculateInterest($penaltableAmount, $interestRate, $i);
            $penaltableAmount = $penaltableAmount + $interestAmount;

            $paymentStructure[] = [
                'returnMonth' => $date->monthName,
                'taxAmount' => $penaltableAMountForPerticularMonth,
                'penaltyAmount' => $penaltableAmount,
                'lateFilingAmount' =>  0,
                'latePaymentAmount' => 0,
                'interestRate' => $interestRate,
                'interestAmount' => $interestAmount,
            ];

            $penaltableAMountForPerticularMonth = $penaltableAmount;
            $date->addMonth();

        }
        return $paymentStructure;
    }

    public function getTotals($financialMonth, $taxAmount, $taxTypeCurency){
        $penalty = $this->getTotalPenalties($financialMonth, $taxAmount, $taxTypeCurency);

        if (count($penalty)){
            return [
                'total' => end($penalty)['penaltyAmount'],
                'penalty' => end($penalty)['penaltyAmount'] - $taxAmount,
                'interest' => 0
            ];
        }
        else {
            return [
                'total' => $taxAmount,
                'penalty' => 0,
                'interest' => 0
            ];
        }
    }

    public function isLateFiling($financialMonth){
        // We have filing month
        if(Carbon::now()->greaterThan($financialMonth->due_date)){
            return true;
        }

        return false;
    }

    public function getLateFilingFee($financialMonth, $taxAmount, $taxTypeCurency){
        $lateFilingRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'LF')->firstOrFail()->rate; // 100,000
        $percentageFee = $lateFilingRate * $taxAmount; // 0
        $weGRate = $lateFilingRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'WEG')->firstOrFail()->rate; // 100,000

        $rate = 1;

        if($taxTypeCurency != 'TZS') {
            $rate = self::getExchangeRate($taxTypeCurency);
            $percentageFee = ($percentageFee * $rate);
        }

        if($percentageFee >= $weGRate){

            return ($percentageFee / $rate);
        }

        if($taxTypeCurency != 'TZS') {
            $weGRate = ($weGRate / $rate);
        }

        return $weGRate;
    }

    public function getFilingMonth($locationId, $ReturnClass){
        // Check last return w/ Status
        if($return = $ReturnClass::where('business_location_id', $locationId)->latest()->first()){
            if($return->status === 'complete'){
                return $this->checkNextViableReturnMonth($return->financialMonth);
            } else {
                return $return->financialMonth;
            }
        }

        // If not, Check date of commence
        $date = BusinessLocation::findOrFail($locationId)->business->effective_date;
        $financialYear = FinancialYear::where('code', $date->year)->firstOrFail();
        $financialMonth = FinancialMonth::where('financial_year_id', $financialYear->id)
                                ->where('number', $date->month)
                                ->firstOrFail();
        return $this->checkNextViableReturnMonth($financialMonth);
    }

    public function checkNextViableReturnMonth($financialMonth){
        // Get next return month,
        // check if 12, add year, get first month
        if($financialMonth->number === 12){
            $year = FinancialYear::where('code', $financialMonth->year->code + 1);
            $month = FinancialMonth::where('number', 1)
                ->where('financial_year_id', $year->id)
                ->firstOrFail();
        } else {
            $month = FinancialMonth::where('financial_year_id', $financialMonth->financial_year_id)
                ->where('number', $financialMonth->number + 1)
                ->firstOrFail();
        }
        
        $date = $this->getDateFromFinancialMonth($month);
        // Compare with current date
        if($date->lessThanOrEqualTo(Carbon::now())){
            return $month;
        } else {
            return false;
        }
    }

    public function getDateFromFinancialMonth($financialMonth){
        return Carbon::create($financialMonth->year->code, $financialMonth->number, 1);
    }

    public function calculateInterest($taxAmount, $rate, $period){
        $interest = ($taxAmount * pow((1 + $rate), $period)) - $taxAmount;
        return $interest;
    }

    public function getCurrentFinancialMonth() {
        $now = Carbon::now();

        $financialYear = FinancialYear::where('code', $now->year)->firstOrFail();
        $financialMonth = FinancialMonth::where('financial_year_id', $financialYear->id)
                                ->where('number', ($now->month))
                                ->firstOrFail();
        return $financialMonth;
    }
}

