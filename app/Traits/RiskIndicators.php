<?php

namespace App\Traits;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\RiskIndicator;
use Carbon\Carbon;

trait RiskIndicators
{
    public function checkRiskIndicators($taxReturn)
    {
        $riskIndicators = [];

        //Nil Return for three consecutive months
        if ($taxReturn->is_nill == 1) {
            $previousReturns = $taxReturn->getPreviousReturns(2); // Retrieve 2 previous returns
            $consecutiveNilReturns = true;

            foreach ($previousReturns as $previousReturn) {
                if ($previousReturn->is_nill != 1) {
                    $consecutiveNilReturns = false;
                    break;
                }
            }

            if ($consecutiveNilReturns) {
                $riskIndicators[] = RiskIndicator::where('slug', 'nil_return_3m')->first()->id;
            }
        }


        //All Credit returns
        if ($taxReturn->has_claim == 1) {
            $riskIndicators[] = RiskIndicator::where('slug', 'all_credit_return')->first()->id;
        }


        //Taxpayer who didn’t declare purchases for three consecutive months 
        if ($taxReturn->has_claim == 1) {
            $previousReturns = $taxReturn->getPreviousReturns(2); // Retrieve 2 previous returns
            $consecutiveClaimReturns = true;

            foreach ($previousReturns as $previousReturn) {
                if ($previousReturn->has_claim !== 1) {
                    $consecutiveClaimReturns = false;
                    break;
                }
            }

            if ($consecutiveClaimReturns) {
                $riskIndicators[] = RiskIndicator::where('slug', 'no_purchase_3m')->first()->id;
            }
        }


        if ($taxReturn->return_type == VatReturn::class) {

            // Sales vs purchases difference is less than or equal to 10%
            $sales = $taxReturn->return->total_input_tax; 
            $purchases = $taxReturn->return->total_output_tax; 
            $tolerance = abs($sales) * 0.1; 
            
            if (abs($sales - $purchases) <= $tolerance) {
                $riskIndicators[] = RiskIndicator::where('slug', 'sale_purchase_diff_10')->first()->id;
            }
            
            // Check for hotel business type and purchases exceeding 1/3 of sales
            if ($taxReturn->return->business_type === 'hotel' && $purchases > (3 * $sales)) {
                $riskIndicators[] = RiskIndicator::where('slug', 'hotel_purchase_exceed')->first()->id;
            }
        }
        

        // Non-Filer for three Consecutive Months
        $lastReturn = $taxReturn->getPreviousReturns(1)->first();

        if ($lastReturn === null || Carbon::now()->diffInMonths($lastReturn->created_at) >= 3) {

            $riskIndicators[] = RiskIndicator::where('slug', 'non_filer_3m')->first()->id;
        }

        // Trends of tax paid for the month and other month differ by less than or equal to 10%
        $averageTax = $this->getAverageTaxPaidForPastMonths($taxReturn, 1);
        $tolerance = $averageTax * 0.1;

        if (abs($taxReturn->principal - $averageTax) > $tolerance) {
            $riskIndicators[] = RiskIndicator::where('slug', 'tax_trend_diff_10')->first()->id;
        }

        return $riskIndicators;
    }

    protected function getAverageTaxPaidForPastMonths($taxReturn, int $count)
    {
        $pastReturns = $taxReturn->getPreviousReturns($count);
        $totalTaxPaid = 0;
        foreach ($pastReturns as $pastReturn) {
            $totalTaxPaid += $pastReturn->principal;
        }
        return $totalTaxPaid / ($count ? $count : 1); // Avoid division by zero
    }

}
