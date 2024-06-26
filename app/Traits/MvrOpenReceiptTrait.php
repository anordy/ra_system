<?php

namespace App\Traits;

use App\Enum\TransactionType;
use App\Models\Business;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Models\TaxType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait MvrOpenReceiptTrait
{
    public function hasOpenReceipt($userId) {
        $businessIds = Business::query()
            ->where('taxpayer_id', $userId)
            ->select('id')
            ->get()->pluck('id');

        $taxTypes = TaxType::query()
            ->where('code', TaxType::PUBLIC_SERVICE)
            ->select('id')
            ->get()->pluck('id');

        $transactions = TaxpayerLedger::query()
            ->whereIn('tax_type_id', $taxTypes)
            ->where('transaction_type', TransactionType::DEBIT)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('taxpayer_ledgers as t2')
                    ->whereColumn('t2.source_type', 'taxpayer_ledgers.source_type')
                    ->whereColumn('t2.source_id', 'taxpayer_ledgers.source_id')
                    ->where('t2.transaction_type', TransactionType::CREDIT);
            })
            ->where('taxpayer_id', $userId)
            // For business
            ->whereIn('business_id', $businessIds)
            ->where('transaction_date', '<', Carbon::now()->subHours(24))
            ->exists();

        // Check if public service has not been filed.

        return $transactions;
    }
}