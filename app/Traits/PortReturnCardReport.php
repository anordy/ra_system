<?php

namespace App\Traits;

use App\Models\Returns\ReturnStatus;
use Illuminate\Support\Facades\DB;


trait PortReturnCardReport
{

    public function returnCardReportForPaidReturns($returnClass, $returnTableName, $penaltyTableName){

        $penaltyData = $returnClass::whereIn("{$returnTableName}.status", [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT])->leftJoin("{$penaltyTableName}", "{$returnTableName}.id", '=', "{$penaltyTableName}.return_id")
        ->where("{$penaltyTableName}.currency", 'TZS')
        ->select(
            DB::raw("SUM(".$penaltyTableName.".late_filing) as totalLateFiling"),
            DB::raw("SUM(".$penaltyTableName.".late_payment) as totalLatePayment"),
            DB::raw("SUM(".$penaltyTableName.".rate_amount) as totalRate"),
        )
        ->groupBy('return_id')
        ->get();

        $returnQuery = $returnClass::where("currency", 'TZS')->whereIn('status', [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT]);

        return  [
            'totalTaxAmount' => $returnQuery->sum("{$returnTableName}.total_amount_due_with_penalties"),
            'totalLateFiling' => $penaltyData->sum('totalLateFiling'),
            'totalLatePayment' => $penaltyData->sum('totalLatePayment'),
            'totalRate' => $penaltyData->sum('totalRate'),
        ];

    }


    public function returnCardReportForUnpaidReturns($returnClass, $returnTableName, $penaltyTableName){

        
        $penaltyData = $returnClass::whereNotIn("{$returnTableName}.status", [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT])->leftJoin("{$penaltyTableName}", "{$returnTableName}.id", '=', "{$penaltyTableName}.return_id")
        ->where("{$penaltyTableName}.currency", 'TZS')
        ->select(
            DB::raw("SUM(".$penaltyTableName.".late_filing) as totalLateFiling"),
            DB::raw("SUM(".$penaltyTableName.".late_payment) as totalLatePayment"),
            DB::raw("SUM(".$penaltyTableName.".rate_amount) as totalRate"),
        )
        ->groupBy('return_id')
        ->get();

        $returnQuery = $returnClass::where("currency", 'TZS')->whereNotIn('status', [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT]);

        return [
            'totalTaxAmount' => $returnQuery->sum("{$returnTableName}.total_amount_due_with_penalties"),
            'totalLateFiling' => $penaltyData->sum('totalLateFiling'),
            'totalLatePayment' => $penaltyData->sum('totalLatePayment'),
            'totalRate' => $penaltyData->sum('totalRate'),
        ];

    }
    
}