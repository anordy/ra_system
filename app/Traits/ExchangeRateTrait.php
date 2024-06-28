<?php

namespace App\Traits;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Log;

trait ExchangeRateTrait
{

    public static function getExchangeRate($currency)
    {
        try {
            if ($currency == Currency::TZS) {
                return 1;
            } else {
                $rate = ExchangeRate::where('currency', $currency)
                    ->whereRaw("TO_CHAR(exchange_date, 'mm') = TO_CHAR(SYSDATE, 'mm')
                            AND TO_CHAR(exchange_date, 'yyyy') = TO_CHAR(SYSDATE, 'yyyy')")
                    ->where('is_approved', true)
                    ->first()
                    ->toArray();

                if (count($rate)) {
                    $exchangeRate = $rate['mean'];

                    if ($currency != Currency::TZS && (!is_numeric($exchangeRate) || $exchangeRate <= 0)) {
                        throw new \Exception('Please provide exchange rate for non TZS currency');
                    }

                    return floatval($exchangeRate);
                } else {
                    throw new \Exception('Exchange rate does not exist');
                }
            }
        } catch (\Exception $exception) {
            Log::error('TRAITS-EXCHANGE-RATE-TRAIT-GET-EXCHANGE-RATE', [$exception]);
            throw $exception;
        }
    }

}
