<?php

namespace App\Traits;

use App\Models\Returns\ReturnStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait ReturnFilterTrait
{
    //filter data according to user criteria
    public function dataFilter($filter, $data, $returnTable)
    {
        if ($data == []) {
            $filter->whereMonth($returnTable . '.created_at', '=', date('m'));
            $filter->whereYear($returnTable . '.created_at', '=', date('Y'));
        }
        if (isset($data['type']) && $data['type'] != 'all') {
            $filter->Where('return_category', $data['type']);
        }
        if (isset($data['year']) && $data['year'] != 'All' && $data['year'] != 'Custom Range') {
            $filter->whereYear($returnTable . '.created_at', '=', $data['year']);
        }
        if (isset($data['month']) && $data['month'] != 'all' && $data['year'] != 'Custom Range') {
            $filter->whereMonth($returnTable . '.created_at', '=', $data['month']);
        }
        if (isset($data['year']) && $data['year'] == 'Custom Range') {
            $from = Carbon::create($data['from'])->startOfDay();
            $to   = Carbon::create($data['to'])->endOfDay();
            $filter->whereBetween($returnTable . '.created_at', [$from, $to]);
        }
        
        return $filter;
    }

    public function getSummaryData($model)
    {
        $m1 = clone $model;
        $m2 = clone $model;
        $m3 = clone $model;
        $m4 = clone $model;
        $m5 = clone $model;
        $m6 = clone $model;

        //All Filings
        $vars['totalSubmittedReturns'] = $m1->count();

        //late filings
        $vars['totalLateFiledReturns'] = $m2->whereColumn('created_at', '>', 'filing_due_date')->count();

        //In-Time filings
        $vars['totalInTimeFiledReturns'] = $m3->whereColumn('created_at', '<=', 'filing_due_date')->count();

        //All paid returns
        $vars['totalPaidReturns'] = $m4->whereNotNull('paid_at')->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = $m5->whereNull('paid_at')->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = $m6->whereColumn('paid_at', '>', 'payment_due_date')->count();

        return $vars;
    }

    //paid Returns
    public function paidReturns($returnClass, $returnTableName, $penaltyTableName)
    {
        $penaltyData = $returnClass->where("{$returnTableName}.status", [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT])->leftJoin("{$penaltyTableName}", "{$returnTableName}.id", '=', "{$penaltyTableName}.return_id")
        ->select(
            DB::raw('SUM(' . $penaltyTableName . '.late_filing) as totalLateFiling'),
            DB::raw('SUM(' . $penaltyTableName . '.late_payment) as totalLatePayment'),
            DB::raw('SUM(' . $penaltyTableName . '.rate_amount) as totalRate'),
        )
        ->groupBy('return_id')
        ->get();

        $returnQuery = $returnClass->where('status', [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT]);

        return  [
            'totalTaxAmount'   => $returnQuery->sum("{$returnTableName}.total_amount_due_with_penalties"),
            'totalLateFiling'  => $penaltyData->sum('totalLateFiling'),
            'totalLatePayment' => $penaltyData->sum('totalLatePayment'),
            'totalRate'        => $penaltyData->sum('totalRate'),
        ];
    }

    //unpaid Returns
    public function unPaidReturns($returnClass, $returnTableName, $penaltyTableName)
    {
        $penaltyData = $returnClass->whereNotIn("{$returnTableName}.status", [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT])->leftJoin("{$penaltyTableName}", "{$returnTableName}.id", '=', "{$penaltyTableName}.return_id")
        ->select(
            DB::raw('SUM(' . $penaltyTableName . '.late_filing) as totalLateFiling'),
            DB::raw('SUM(' . $penaltyTableName . '.late_payment) as totalLatePayment'),
            DB::raw('SUM(' . $penaltyTableName . '.rate_amount) as totalRate'),
        )
        ->groupBy('return_id')
        ->get();

        $returnQuery = $returnClass->whereNotIn('status', [ReturnStatus::COMPLETE, ReturnStatus::PAID_BY_DEBT]);

        return  [
            'totalTaxAmount'   => $returnQuery->sum("{$returnTableName}.total_amount_due_with_penalties"),
            'totalLateFiling'  => $penaltyData->sum('totalLateFiling'),
            'totalLatePayment' => $penaltyData->sum('totalLatePayment'),
            'totalRate'        => $penaltyData->sum('totalRate'),
        ];
    }
}
