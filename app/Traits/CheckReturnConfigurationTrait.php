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
                    $payload = ['currentYear' => $nextFinancialYear];
                    event(new SendMail('financial-year', $payload));
                    event(new SendSms('financial-year', $payload));
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
                    $payload = ['currentYear' => $currentYear, 'currentMonth' => $filingFinancialMonth];
                    event(new SendMail('financial-month', $payload));
                    event(new SendSms('financial-month', $payload));
                    return false;
                }
            }
        } else {
            Log::error("{$currentYear} FINANCIAL YEAR DOES NOT EXIST");
            $payload = ['currentYear' => $currentYear];
            event(new SendMail('financial-year', $payload));
            event(new SendSms('financial-year', $payload));
            return false;
        }
    }


    /**
     * Check if penalty rate exists
     */
    public function doesPenaltyRateExists()
    {
        $currentYear = date('Y');
        $financialYear = FinancialYear::where('code', $currentYear)->first();

        $penaltyRates = PenaltyRate::where('financial_year_id', $financialYear->id)->get();

        // If penalty rates exists return true, otherwise false (We check if LF, LPB, LPA, WEG, MNO/BFO exists)
        if (count($penaltyRates) >= 5) {
            return true;
        } else {
            Log::error("{$currentYear} PENALTY RATES DOES NOT EXIST");
            $payload = ['currentYear' => $currentYear];
            event(new SendMail('penalty-rate', $payload));
            event(new SendSms('penalty-rate', $payload));
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
                $payload = ['currentYear' => $currentYear];
                event(new SendMail('interest-rate', $payload));
                event(new SendSms('interest-rate', $payload));
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Check if exchange rate exists
     */
    public function doesExchangeRateExists()
    {
        $currencies = Currency::all();
        $currencies_statuses = [];

        foreach ($currencies as $currency) {
            // Ignore TZS currency as rate will be always 1
            if ($currency->iso != 'TZS') {

                $currencyRate = ExchangeRate::query()
                    ->where('exchange_date', '<=', Carbon::now()->toDateTimeString())
                    ->where('currency', $currency->iso)->first();

                // If no exchange rate add to currencies_statuses
                if (!$currencyRate) {
                    $payload = ['currency' => $currency->iso, 'date' => Carbon::now()->toDateString()];
                    event(new SendMail('exchange-rate', $payload));
                    event(new SendSms('exchange-rate', $payload));
                    $currencies_statuses[] = [
                        'status' => false,
                        'description' => "Todays {$currency->iso} exchange rate has not been configured",
                        'route' => 'settings.exchange-rate.index'
                    ];
                }
            }
        }
        return $currencies_statuses;
    }
}
