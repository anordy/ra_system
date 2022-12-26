<?php

namespace App\Services\ZanMalipo;

use App\Models\TaxType;
use App\Models\TransactionFee;

class ZmFeeHelper
{
    /**
     * @param array $billItems
     * @param $currency
     * @param $exchangeRate
     * @return array
     * @throws \Exception
     */
    public static function addTransactionFee(array $billItems, $currency, $exchangeRate): array
    {
        if (!is_numeric($exchangeRate) || $exchangeRate <= 0) {
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

        if (!is_numeric($bill_amount) || $bill_amount <= 0) {
            throw new \Exception("Bill amount can not be zero, null or not a number.");
        }

        $equivalent_amount = $bill_amount * $exchangeRate;

        $transactionFee = TransactionFee::whereNull('maximum_amount')->select('minimum_amount', 'fee')->first();
        if ($transactionFee == null) {
            return 0;
        }
        $minFee = $transactionFee->minimum_amount;

        //if the amount exceed the maximum fee range we take the constant fee
        if ($minFee <= $equivalent_amount) {
            $fee = $transactionFee->fee;
        } else {
            $fee = TransactionFee::where('minimum_amount', '<=', $equivalent_amount)->where('maximum_amount', '>=', $equivalent_amount)->pluck('fee')->first();
            $fee = $fee * $equivalent_amount;
        }

        if ($currency != 'TZS') {
            $fee = round($fee / $exchangeRate, 2);
        }

        $billItems[] = [
            'use_item_ref_on_pay' => 'N',
            'amount' => round($fee, 2),
            'currency' => $currency,
            'gfs_code' => $item['gfs_code'],
            'tax_type_id' => TaxType::where('code', TaxType::GOVERNMENT_FEE)->firstOrFail()->id,
        ];

        return $billItems;
    }
}
