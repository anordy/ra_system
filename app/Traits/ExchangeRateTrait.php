<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\ExchangeRate;

trait ExchangeRateTrait
{

    public static function getExchangeRate($currency)
    {
        if ($currency == 'TZS') {
            return 1;
        } else {
            $rate      = ExchangeRate::where('currency', $currency)->where('exchange_date','<=', Carbon::now()->toDateTimeString())->first();
            if (!$rate) {
                throw new \Exception('Exchange rate does not exist');
            }
    
            $exchangeRate = $rate->mean;
    
            if ($currency != 'TZS' && (!is_numeric($exchangeRate) || $exchangeRate <= 0)) {
                throw new \Exception('Please provide exchange rate for non TZS currency');
            }
    
            return $exchangeRate;
        }

    }
   
}
