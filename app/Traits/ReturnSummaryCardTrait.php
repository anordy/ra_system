<?php

namespace App\Traits;

use Carbon\Carbon;

trait ReturnSummaryCardTrait
{
    public function getSummaryData($model)
    {
        //first day of the month
        $from = Carbon::now()->firstOfMonth()->toDateTimeString();

        //last day of the month
        $to = Carbon::now()->lastOfMonth()->toDateTimeString();

        //All Filings
        $vars['totalSubmittedReturns'] = $model->whereBetween('created_at', [$from, $to])->count();

        //late filings
        $vars['totalLateFiledReturns'] = $model->whereBetween('created_at', [$from, $to])->where('filing_due_date', '<', 'created_at')->count();

        //In-Time filings
        $vars['totalInTimeFiledReturns'] = $model->whereBetween('created_at', [$from, $to])->where('filing_due_date', '>=', 'created_at')->count();

        //All paid returns
        $vars['totalPaidReturns'] = $model->whereBetween('created_at', [$from, $to])->whereNotNull('paid_at')->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = $model->whereBetween('created_at', [$from, $to])->whereNull('paid_at')->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = $model->whereBetween('created_at', [$from, $to])->where('payment_due_date', '<', 'paid_at')->count();

        return $vars;
    }
}
