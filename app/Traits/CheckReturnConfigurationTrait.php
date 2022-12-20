<?php

namespace App\Traits;

use App\Models\FinancialYear;
use App\Models\FinancialMonth;
use App\Models\InterestRate;
use App\Models\PenaltyRate;
use Illuminate\Support\Facades\Log;

trait CheckReturnConfigurationTrait
{

    /**
     * Check if current financial month exists, this checks both financial year and financial month
     */
    public function doesCurrentFinancialMonthExists()
    {
        $currentYear = date('Y');
        $currentMonth = date('n');

        $financialYear = FinancialYear::where('code', $currentYear)->first();

        // Check if financial year exists
        if ($financialYear) {
            // If financial year exists check if financial months exists
            $financialMonth = FinancialMonth::where('financial_year_id', $financialYear->id)
                ->where('number', $currentMonth)
                ->first();

            if ($financialMonth && $financialMonth->number == 12) {
                $nextFinancialYear = $financialMonth->year->code + 1;
                $year = FinancialYear::where('code', $nextFinancialYear)->first();

                // Return false if the next financial year does not exist
                if (!$year) {
                    Log::error("{$nextFinancialYear} FINANCIAL YEAR DOES NOT EXIST");
                    return false;
                } else {
                    // If next financial year exists, get the january month
                    $month = FinancialMonth::where('number', 1)
                        ->where('financial_year_id', $year->id)
                        ->first();
                    return true;
                }
            } else {
                $filingFinancialMonth = $financialMonth->number + 1;
                $filingFinancialYearId = $financialMonth->financial_year_id;
                $month = FinancialMonth::where('financial_year_id', $filingFinancialYearId)
                    ->where('number', $filingFinancialMonth)
                    ->first();

                if ($month) {
                    return true;
                } else {
                    Log::error("FINANCIAL MONTH {$filingFinancialMonth} FOR THE YEAR {$currentYear} DOES NOT EXIST");
                    return false;
                }
            }
        } else {
            Log::error("{$currentYear} FINANCIAL YEAR DOES NOT EXIST");
            return false;
        }
    }


    /**
     * Check if penalty rate exists
     */
    public function doesPenaltyRateExists()
    {
        // Check if current financial month exists first
        if ($this->doesCurrentFinancialMonthExists()) {
            $currentYear = date('Y');
            $financialYear = FinancialYear::where('code', $currentYear)->first();

            $penaltyRates = PenaltyRate::where('financial_year_id', $financialYear->id)->get();

            // If penalty rates exists return true, otherwise false
            if (count($penaltyRates) > 0) {
                return true;
            } else {
                Log::error("{$currentYear} PENALTY RATES DOES NOT EXIST");
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Check if interest rate exists
     */
    public function doesInterestRateExists()
    {
        // Check if current financial month exists first
        if ($this->doesCurrentFinancialMonthExists()) {
            $currentYear = date('Y');

            $interestRate = InterestRate::where('year', $currentYear)->first();

            // If penalty rates exists return true, otherwise false
            if ($interestRate) {
                return true;
            } else {
                Log::error("{$currentYear} INTEREST RATE DOES NOT EXIST");
                return false;
            }
        } else {
            return false;
        }
    }
}
