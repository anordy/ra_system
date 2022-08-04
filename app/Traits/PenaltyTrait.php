<?php

namespace App\Traits;

use App\Models\BusinessLocation;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\InterestRate;
use App\Models\PenaltyRate;
use Carbon\Carbon;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Support\Facades\Log;

trait PenaltyTrait
{

    public function getTotalPenalties($financialMonth, $taxAmount, $taxTypeCurency){
        $lateFilingFee = 0;
        // dd($financialMonth->due_date);
        if($this->isLateFiling($financialMonth)){
            $lateFilingFee = $this->getLateFilingFee($financialMonth, $taxAmount, $taxTypeCurency);
        } 

        // Get late payments penalties
        $penaltableAmount = 0;

        $date = $this->getDateFromFinancialMonth($financialMonth);
        // dd($date);
        $diffInMonths = $date->diffInMonths(Carbon::now());
        $interestRate = InterestRate::where('year', $financialMonth->year->code)->firstOrFail()->rate;
        $latePaymentBeforeRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'LPB')->firstOrFail()->rate;
        $latePaymentAfterRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'LPA')->firstOrFail()->rate;

        // if(!$interestRate){
        //     Log::error("Interest for the year {$financialMonth->year->code}");
        //     abort(404);
        // }

        // if(!$lateFilingFee){
        //     Log::error("Late filing for the year {$financialMonth->year->code}");
        //     abort(404);
        // }

        // if(!$latePaymentBeforeRate){
        //     Log::error("Late payment for the year {$financialMonth->year->code}");
        //     abort(404);
        // }

        // if(!$latePaymentAfterRate){
        //     Log::error("Late payment for the year {$financialMonth->year->code}");
        //     abort(404);
        // }

        $paymentStructure = [];
        $penaltableAMountForPerticularMonth = 0;
        for ($i = 0; $i < $diffInMonths; $i++) { 
            if($i === 0){

                $penaltableAmount = $lateFilingFee + $taxAmount;
                $latePaymentAmount = ($latePaymentBeforeRate * $penaltableAmount);
                $penaltableAmount = $latePaymentAmount + $penaltableAmount;
                $interestAmount = $this->calculateInterest($penaltableAmount, $interestRate, $i);
                $penaltableAmount = $interestAmount + $penaltableAmount;

                $penaltableAMountForPerticularMonth = $penaltableAmount;

                $paymentStructure[] = [
                    'returnMonth' => $date->monthName,
                    'taxAmount' => $taxAmount,
                    'penaltyAmount' => $penaltableAMountForPerticularMonth,
                    'lateFilingAmount' => $lateFilingFee ?? 0,
                    'latePaymentAmount' => $latePaymentAmount ?? 0,
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
                'latePaymentAmount' => $latePaymentAmount ?? 0,
                'interestRate' => $interestRate,
                'interestAmount' => $interestAmount
            ];
            $penaltableAMountForPerticularMonth = $penaltableAmount;
            $date->addMonth();

        }
        return $paymentStructure;
    }

    public function isLateFiling($financialMonth){
        // We have filing month
        if(Carbon::now()->greaterThan($financialMonth->due_date)){
            return true;
        }

        return false;
    }

    public function getLateFilingFee($financialMonth, $taxAmount, $taxTypeCurency){
        $lateFilingRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'LF')->firstOrFail()->rate;
        $percentageFee = $lateFilingRate * $taxAmount;
        $weGRate = $lateFilingRate = PenaltyRate::where('financial_year_id', $financialMonth->year->id)->where('code', 'WEG')->firstOrFail()->rate;

        $rate = 1;
        if($taxTypeCurency !== 'TZS') {
            $rate = 2300;
            $percentageFee = $this->checkCurrency($percentageFee, $rate);
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

    public function getFilingMonth($locationId, $ReturnClass){
        // Check last return w/ Status
        if($return = $ReturnClass::where('business_location_id', $locationId)->first()){
            if($return->status === 'complete'){
                return $this->checkNextViableReturnMonth($return->financialMonth);
            } else {
                return $return->financialMonth;
            }
        }

        // If not, Check date of commence
        $date = BusinessLocation::find($locationId)->business->date_of_commencing;
        $financialYear = FinancialYear::where('code', $date->year)->first();
        $financialMonth = FinancialMonth::where('financial_year_id', $financialYear->id)
                                ->where('number', $date->month)
                                ->first();
        // dd($financialMonth);
        return $this->checkNextViableReturnMonth($financialMonth);

    }

    public function checkNextViableReturnMonth($financialMonth){
        // Get next return month, 
        // check if 12, add year, get first month
        if($financialMonth->number === 12){
            $year = FinancialYear::where('code', $financialMonth->year->code + 1);
            $month = FinancialMonth::where('number', 1)
                        ->where('financial_year_id', $year->id)
                        ->first();
        } else {
            $month = FinancialMonth::where('financial_year_id', $financialMonth->financial_year_id)
                        ->where('number', $financialMonth->number + 1)
                        ->first();
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
}

