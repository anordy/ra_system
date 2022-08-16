<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait HotelLevyCardReport
{

    public function hotelLevyCardReport($returnClass, $return, $penalty, $tax_type_id)
    {

        $penaltyData = $returnClass::where('tax_type_id', $tax_type_id)->where("{$return}_returns.status", '!=', 'complete')->leftJoin("{$penalty}_penalties", "{$return}_returns.id", '=', "{$penalty}_penalties.return_id")
            ->select(
                DB::raw("SUM(" . $penalty . "_penalties.late_filing) as totalLateFiling"),
                DB::raw("SUM(" . $penalty . "_penalties.late_payment) as totalLatePayment"),
                DB::raw("SUM(" . $penalty . "_penalties.rate_amount) as totalRate"),
            )
            ->groupBy('return_id')
            ->get();

        $returnQuery = $returnClass::where('tax_type_id', $tax_type_id)->where('status', '!=', 'complete');

        return  [
            'totalTaxAmount' => $returnQuery->sum("{$return}_returns.total_amount_due_with_penalties"),
            // 'totalPrincipalAmount' => $return->sum("{$return}_returns.total_amount_due"),
            'totalLateFiling' => $penaltyData->sum('totalLateFiling'),
            'totalLatePayment' => $penaltyData->sum('totalLatePayment'),
            'totalRate' => $penaltyData->sum('totalRate'),
        ];
    }
}
