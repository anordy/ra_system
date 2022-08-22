<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;


trait ReturnCardReport
{

    public function returnCardReportForPaidReturns($returnClass, $returnTableName, $penaltyTableName){

        $penaltyData = $returnClass::where("{$returnTableName}.status", 'complete')->leftJoin("{$penaltyTableName}", "{$returnTableName}.id", '=', "{$penaltyTableName}.return_id")
        ->select(
            DB::raw("SUM(".$penaltyTableName.".late_filing) as totalLateFiling"),
            DB::raw("SUM(".$penaltyTableName.".late_payment) as totalLatePayment"),
            DB::raw("SUM(".$penaltyTableName.".rate_amount) as totalRate"),
        )
        ->groupBy('return_id')
        ->get();

        $returnQuery = $returnClass::where('status', 'complete');

        return  [
            'totalTaxAmount' => $returnQuery->sum("{$returnTableName}.total_amount_due_with_penalties"),
            'totalLateFiling' => $penaltyData->sum('totalLateFiling'),
            'totalLatePayment' => $penaltyData->sum('totalLatePayment'),
            'totalRate' => $penaltyData->sum('totalRate'),
        ];

    }


    public function returnCardReportForUnpaidReturns($returnClass, $returnTableName, $penaltyTableName){

        
        $penaltyData = $returnClass::where("{$returnTableName}.status", '!=', 'complete')->leftJoin("{$penaltyTableName}", "{$returnTableName}.id", '=', "{$penaltyTableName}.return_id")
        ->select(
            DB::raw("SUM(".$penaltyTableName.".late_filing) as totalLateFiling"),
            DB::raw("SUM(".$penaltyTableName.".late_payment) as totalLatePayment"),
            DB::raw("SUM(".$penaltyTableName.".rate_amount) as totalRate"),
        )
        ->groupBy('return_id')
        ->get();

        $returnQuery = $returnClass::where('status', '!=', 'complete');

        return  [
            'totalTaxAmount' => $returnQuery->sum("{$returnTableName}.total_amount_due_with_penalties"),
            'totalLateFiling' => $penaltyData->sum('totalLateFiling'),
            'totalLatePayment' => $penaltyData->sum('totalLatePayment'),
            'totalRate' => $penaltyData->sum('totalRate'),
        ];

    }

    
}