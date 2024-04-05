<?php

namespace App\Traits;

use App\Models\TaxType;
use App\Models\ZmPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait DailyPaymentTrait
{
    private $start;
    private $end;

    public function getInvolvedTaxTypes($range_start, $range_end)
    {

        try {
            $this->start = Carbon::parse($range_start)->startOfDay()->toDateTimeString();
            $this->end = Carbon::parse($range_end)->endOfDay()->toDateTimeString();

            $taxTypes = TaxType::whereIn('id', function ($query) {
                $query->select('zm_bills.tax_type_id')
                    ->from('zm_payments')
                    ->leftJoin('zm_bills', 'zm_payments.zm_bill_id', 'zm_bills.id')
                    ->whereBetween('zm_payments.trx_time', [$this->start, $this->end])
                    ->distinct();
            })->get();

            return $taxTypes;
        } catch (\Exception $exception) {
            Log::error('TRAITS-DAILY-PAYMENT-TRAIT-GET-INVOLVED-TAX-TYPES', [$exception]);
            throw new \Exception('Failed to Get Total Collection');
        }
    }


    public function getTotalCollectionPerTaxTypeAndCurrency($taxTypeId, $currency, $range_start, $range_end)
    {
        try {
            $start = Carbon::parse($range_start)->startOfDay()->toDateTimeString();
            $end = Carbon::parse($range_end)->endOfDay()->toDateTimeString();

            $totalAmount = ZmPayment::leftJoin('zm_bills', 'zm_payments.zm_bill_id', 'zm_bills.id')
                ->where('zm_payments.currency', $currency)
                ->whereBetween('zm_payments.trx_time', [$start, $end])
                ->where('zm_bills.tax_type_id', $taxTypeId)
                ->sum('zm_payments.paid_amount');

            return $totalAmount;
        } catch (\Exception $exception) {
            Log::error('TRAITS-DAILY-PAYMENT-TRAIT-GET-TOTAL-COLLECTION-PER-TAX-TYPE-AND-CURRENCY', [$exception]);
            throw new \Exception('Failed to Get Total Collection');
        }

    }

    public function getTotalCollectionPerCurrency($currency, $range_start, $range_end)
    {
        try {
            $start = Carbon::parse($range_start)->startOfDay()->toDateTimeString();
            $end = Carbon::parse($range_end)->endOfDay()->toDateTimeString();

            $totalAmount = ZmPayment::where('currency', $currency)
                ->whereBetween('trx_time', [$start, $end])
                ->sum('paid_amount');
            return $totalAmount;
        } catch (\Exception $exception) {
            Log::error('TRAITS-DAILY-PAYMENT-TRAIT-GET-TOTAL-COLLECTION-PER-CURRENCY', [$exception]);
            throw new \Exception('Failed to Get Total Collection Per Currency');
        }

    }
}
