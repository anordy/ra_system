<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Events\SendSms;
use App\Events\SendMail;
use App\Models\Currency;
use App\Models\PenaltyRate;
use App\Models\ExchangeRate;
use App\Models\InterestRate;
use App\Models\FinancialYear;
use App\Models\FinancialMonth;
use Illuminate\Support\Facades\Log;

/**
 * Check main return configurations availability
 * 1. Financial Year
 * 2. Financial Months
 * 3. Interest Rate
 * 4. Penalty Rates
 * 5. Exchange Rate
 */
trait CheckReturnConfigurationTrait
{

    /**
     * Get missing configurations ie. Financial year, Financial Month, Interest rate & Penalty Rate
     */
    public function getMissingConfigurations()
    {
        $issues = [];

        $currentYear = date('Y');
        $currentMonth = date('n');

        $financialYear = FinancialYear::where('code', $currentYear)->first();

        // Check if financial year exists
        if ($financialYear) {

            if (!$this->doesPenaltyRateExists($financialYear)) {
                $issues[] = [
                    'description' => "{$financialYear->code} penalty rate has not been configured", 
                    'route' => 'settings.penalty-rates.index'
                ];
            }

            if (!$this->doesInterestRateExists($financialYear)) {
                $issues[] = [
                    'description' => "{$financialYear->code} interest rate has not been configured", 
                    'route' => 'settings.interest-rates.index'
                ];
            }


            // If financial year exists check if financial months exists
            $financialMonth = FinancialMonth::where('financial_year_id', $financialYear->id)
                ->where('number', $currentMonth)
                ->first();

            if ($financialMonth && $financialMonth->number == 12) {
                $nextFinancialYear = $financialMonth->year->code + 1;
                $year = FinancialYear::where('code', $nextFinancialYear)->first();

                // Return false if the next financial year does not exist
                if (!$year) {
                    $issues[] = [
                        'description' => "{$nextFinancialYear} financial year has not been configured", 
                        'route' => 'settings.financial-months'
                    ];
                    Log::error("{$nextFinancialYear} FINANCIAL YEAR DOES NOT EXIST");
                    $payload = ['currentYear' => $nextFinancialYear];
                    event(new SendMail('financial-year', $payload));
                    event(new SendSms('financial-year', $payload));
                }
            } else {
                $filingFinancialMonth = $financialMonth->number + 1;
                $filingFinancialYearId = $financialMonth->financial_year_id;
                $month = FinancialMonth::where('financial_year_id', $filingFinancialYearId)
                    ->where('number', $filingFinancialMonth)
                    ->first();

                if (!$month) {
                    $issues[] = [
                        'description' => "Financial month {$filingFinancialMonth} for the year {$currentYear} financial year has not been configured", 
                        'route' => 'settings.financial-months'
                    ];
                    Log::error("FINANCIAL MONTH {$filingFinancialMonth} FOR THE YEAR {$currentYear} DOES NOT EXIST");
                    $payload = ['currentYear' => $currentYear, 'currentMonth' => $filingFinancialMonth];
                    event(new SendMail('financial-month', $payload));
                    event(new SendSms('financial-month', $payload));
                }
            }

        } else {
            $issues[] = [
                'description' => "{$currentYear} financial year has not been configured", 
                'route' => 'settings.financial-months'
            ];
            Log::error("{$currentYear} FINANCIAL YEAR DOES NOT EXIST");
            $payload = ['currentYear' => $currentYear];
            event(new SendMail('financial-year', $payload));
            event(new SendSms('financial-year', $payload));
        }

        $all_issues = array_merge($issues, $this->getExchangeRateConfiguration());
        return $all_issues;
    }


    /**
     * Check if penalty rate exists
     */
    public function doesPenaltyRateExists($financialYear)
    {
        $penaltyRates = PenaltyRate::where('financial_year_id', $financialYear->id)->get();

        // If penalty rates exists return true, otherwise false (We check if LF, LPB, LPA, WEG, MNO/BFO exists)
        if (count($penaltyRates) >= 5) {
            return true;
        } else {
            Log::error("{$financialYear->code} PENALTY RATES DOES NOT EXIST");
            $payload = ['currentYear' => $financialYear->code];
            event(new SendMail('penalty-rate', $payload));
            event(new SendSms('penalty-rate', $payload));
            return false;
        }
    }

    /**
     * Check if interest rate exists
     */
    public function doesInterestRateExists($financialYear)
    {
        $interestRate = InterestRate::where('year', $financialYear->code)->first();

        // If penalty rates exists return true, otherwise false
        if ($interestRate) {
            return true;
        } else {
            Log::error("{$financialYear->code} INTEREST RATE DOES NOT EXIST");
            $payload = ['currentYear' => $financialYear->code];
            event(new SendMail('interest-rate', $payload));
            event(new SendSms('interest-rate', $payload));
            return false;
        }
    }

    /**
     * Check if exchange rate exists
     */
    public function getExchangeRateConfiguration()
    {
        $currencies = Currency::all();
        $currencies_statuses = [];

        foreach ($currencies as $currency) {
            // Ignore TZS currency as rate will be always 1
            if ($currency->iso != 'TZS') {

                $currencyRate      = ExchangeRate::where('currency', $currency->iso)
                ->whereRaw("TO_CHAR(exchange_date, 'mm') = TO_CHAR(SYSDATE, 'mm')
                AND TO_CHAR(exchange_date, 'yyyy') = TO_CHAR(SYSDATE, 'yyyy')")
                ->first();

                // If no exchange rate add to currencies_statuses
                if (!$currencyRate) {
                    $month = Carbon::now()->monthName;
                    $payload = ['currency' => $currency->iso, 'date' => $month];
                    event(new SendMail('exchange-rate', $payload));
                    event(new SendSms('exchange-rate', $payload));
                    $currencies_statuses[] = [
                        'description' => "{$currency->iso} exchange rate for month {$month} has not been configured",
                        'route' => 'settings.exchange-rate.index'
                    ];
                }
            }
        }
        return $currencies_statuses;
    }
}
