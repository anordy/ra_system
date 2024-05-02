<?php

namespace App\Traits;


use App\Enum\Currencies;
use App\Enum\TransactionType;
use App\Models\BusinessLocation;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait TaxpayerLedgerTrait
{

    /**
     * Record transaction in ledger
     * @param $transactionType
     * @param $sourceType
     * @param $sourceId
     * @param $principalAmount
     * @param $interestAmount
     * @param $penaltyAmount
     * @param $totalAmount
     * @param $taxTypeId
     * @param $currency
     * @param $taxPayerId
     * @param $locationId
     * @param $financialMonthId
     * @return void
     * @throws \Exception
     */
    public function recordLedger($transactionType, $sourceType, $sourceId, $principalAmount, $interestAmount, $penaltyAmount, $totalAmount, $taxTypeId, $currency, $taxPayerId, $locationId = null, $financialMonthId = null)
    {
        try {
            if (!in_array($transactionType, TransactionType::getConstants())) {
                throw new \Exception('Invalid Transaction Type');
            }

            if (!in_array($currency, Currencies::getConstants())) {
                throw new \Exception('Invalid Currency Provided');
            }

            if ($principalAmount < 0 || $interestAmount < 0 || $penaltyAmount < 0 || $totalAmount < 0) {
                throw new \Exception('Invalid Amount provided');
            }

            if ($locationId) {
                $location = BusinessLocation::find($locationId, ['business_id']);

                if (!$location) {
                    throw new \Exception('Associated business in the provided location not found');
                }
            }

            $ledger = TaxpayerLedger::create([
                'source_type' => $sourceType,
                'source_id' => $sourceId,
                'tax_type_id' => $taxTypeId,
                'taxpayer_id' => $taxPayerId,
                'financial_month_id' => $financialMonthId,
                'transaction_type' => $transactionType,
                'business_id' => $locationId ? $location->business_id : null,
                'business_location_id' => $locationId,
                'currency' => $currency,
                'transaction_date' => Carbon::now(),
                'principal_amount' => $principalAmount,
                'interest_amount' => $interestAmount,
                'penalty_amount' => $penaltyAmount,
                'total_amount' => $totalAmount
            ]);

            if (!$ledger) throw new \Exception('Failed to save ledger transaction');

        } catch (\Exception $exception) {
            Log::error('TRAITS-TAXPAYER-LEDGER-TRAIT-RECORD-LEDGER', [$exception]);
            throw $exception;
        }
    }
}
