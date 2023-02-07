<?php

namespace App\Traits;

use App\Models\Business;
use App\Models\ZmBill;
use Illuminate\Support\Facades\DB;

trait ManagerialReportTrait {
    public function queryDatas($range_start, $range_end, $currency, $filteringForLto, $selectedTaxRegionIds){
        $taxRegionsIds = array_keys($selectedTaxRegionIds->toArray());

        $queryDTD = ZmBill::query()
            ->rightJoin('zm_bill_items', 'zm_bills.id', 'zm_bill_items.zm_bill_id')
            ->select(['zm_bills.tax_type_id as tax_type_id', DB::raw('sum(zm_bill_items.amount) as item_amount')])
            ->groupBy(['zm_bills.tax_type_id'])
            ->whereNotNull(['zm_bill_items.billable_id', 'zm_bill_items.billable_id'])
            ->whereIn('zm_bills.tax_type_id', $this->domesticTaxTypes->pluck('id'))
            //     columns
            ->whereHas('billable', function ($query) use ($taxRegionsIds, $filteringForLto, $currency, $range_start, $range_end){
                $query->whereIn('location_id', $taxRegionsIds);

                // If filtering for LTO
                if ($filteringForLto){
                    $query->whereIn('business_id', Business::query()
                        ->where('is_business_lto', true)
                        ->select('id')
                        ->get()
                        ->toArray());
                }

                // Filter currencies
                $query->where('currency', $currency);

                // Filter Dates
                $query->whereDate('paid_at', '>=', $range_start);
                $query->whereDate('tax_returns.created_at', '<=', $range_end);
            })
            ->with('billable', 'billable.business');

        $queryNTR = ZmBill::query()
            ->rightJoin('zm_bill_items', 'zm_bills.id', 'zm_bill_items.zm_bill_id')
            ->select(['zm_bill_items.tax_type_id as tax_type_id', DB::raw('sum(zm_bill_items.amount) as item_amount')])
            ->groupBy(['zm_bill_items.tax_type_id'])
            ->whereNotNull(['zm_bill_items.billable_id', 'zm_bill_items.billable_id'])
            ->whereIn('zm_bill_items.tax_type_id', $this->nonRevenueTaxTypes->pluck('id'))
            //     columns
            ->whereHas('billable', function ($query) use ($taxRegionsIds, $filteringForLto, $currency, $range_start, $range_end){
                $query->whereIn('location_id', $taxRegionsIds);

                // If filtering for LTO
                if ($filteringForLto){
                    $query->whereIn('business_id', Business::query()
                        ->where('is_business_lto', true)
                        ->select('id')
                        ->get()
                        ->toArray());
                }

                // Filter currency
                $query->where('currency', $currency);

                // Filter Dates
                $query->whereDate('paid_at', '>=', $range_start);
                $query->whereDate('tax_returns.created_at', '<=', $range_end);
            })
            ->with('billable', 'billable.business');

        $report = [];

        foreach ($queryDTD->get() as $item) {
            $report[$item->tax_type_id] = $item->item_amount;
        }

        foreach ($queryNTR->get() as $item) {
            $report[$item->tax_type_id] = $item->item_amount;
        }

        return $report;
    }
}