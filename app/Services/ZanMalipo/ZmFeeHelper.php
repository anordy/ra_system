<?php

namespace App\Services\ZanMalipo;

use App\Models\TaxType;

class ZmFeeHelper
{
    /**
     * @param array $billItems
     * @param $currency
     * @param $exchangeRate
     * @return array
     * @throws \Exception
     */
    public static function addTransactionFee(array $billItems, $currency, $exchangeRate): array {
        if (!is_numeric($exchangeRate) || $exchangeRate <= 0){
            throw new \Exception("Exchange rate can not be zero or null.");
        }

        $bill_amount = 0;
        foreach ($billItems as $item) {
            if (!isset($item['amount']) || !isset($item['gfs_code'])) {
                throw new \Exception('Bill item must contain item_amount and gfs_code');
            }
            if ($item['currency'] != 'TZS') {
                $currency = 'USD';
                $bill_amount += $item['amount'];
            } else {
                $bill_amount += $item['amount'];
            }
        }
        $equivalent_amount = $bill_amount * $exchangeRate;

        $fee = 0;

        switch ($equivalent_amount){
            case $equivalent_amount >= 0.00 && $equivalent_amount <= 100000.00:
                $fee = $bill_amount * 0.025;
                break;
            case $equivalent_amount >= 100001.00 && $equivalent_amount <= 500000.00:
                $fee = $bill_amount * 0.02;
                break;
            case $equivalent_amount >= 500001.00 && $equivalent_amount <= 1000000.00:
                $fee = $bill_amount * 0.013;
                break;
            case $equivalent_amount >= 1000001.00 && $equivalent_amount <= 5000000.00:
                $fee = $bill_amount * 0.003;
                break;
            case $equivalent_amount >= 5000001.00 && $equivalent_amount <= 10000000.00:
                $fee = $bill_amount * 0.0015;
                break;
            case $equivalent_amount >= 10000001.00:
                if ($currency == 'TZS'){
                    $fee = 20000;
                } else {
                    $fee = round(20000 / $exchangeRate, 2);
                }
                break;
            default:
                throw new \Exception('Bill amount out of range.');
        }

        $billItems[] = [
            'use_item_ref_on_pay' => 'N',
            'amount' => $fee,
            'currency' => $currency,
            'gfs_code' => '116101',
            'tax_type_id' => TaxType::where('code', TaxType::GOVERNMENT_FEE)->firstOrFail()->id,
        ];

        return $billItems;
    }
}