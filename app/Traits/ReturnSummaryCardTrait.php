<?php

namespace App\Traits;

use Carbon\Carbon;

trait ReturnSummaryCardTrait
{
    public function getSummaryData($model)
    {
        $m1 = clone $model;
        $m2 = clone $model;
        $m3 = clone $model;
        $m4 = clone $model;
        $m5 = clone $model;
        $m6 = clone $model;
        //first day of the month
        $from = Carbon::now()->firstOfMonth()->toDateTimeString();

        //last day of the month
        $to = Carbon::now()->lastOfMonth()->toDateTimeString();

        //All Filings
        $vars['totalSubmittedReturns'] = $m1->whereBetween('created_at', [$from, $to])->count();

        //late filings
        $vars['totalLateFiledReturns'] = $m2->whereBetween('created_at', [$from, $to])->where('created_at', '>', 'filing_due_date')->count();

        //In-Time filings
        $vars['totalInTimeFiledReturns'] = $m3->whereBetween('created_at', [$from, $to])->where('created_at', '<=', 'filing_due_date')->count();

        //All paid returns
        $vars['totalPaidReturns'] = $m4->whereBetween('created_at', [$from, $to])->whereNotNull('paid_at')->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = $m5->whereBetween('created_at', [$from, $to])->whereNull('paid_at')->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = $m6->whereBetween('created_at', [$from, $to])->where('paid_at', '>', 'payment_due_date')->count();

        return $vars;
    }
}
