<?php

namespace App\Http\Controllers\Returns\BfoExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\BFO\BfoReturn;
use App\Traits\ReturnCardReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BfoExciseDutyController extends Controller
{
    use ReturnCardReport;

    public function index()
    {
        $data = $this->returnCardReport(BfoReturn::class, 'bfo', 'bfo');

        //last day of last month
        $from = Carbon::now()->subMonth()->lastOfMonth()->toDateTimeString();

        //fist day of next month
        $to = Carbon::now()->addMonth()->firstOfMonth()->toDateTimeString();

        //total submitted returns
        $vars['totalSubmittedReturns'] = DB::table('bfo_returns')
            ->join('financial_months', 'financial_months.id', 'bfo_returns.financial_month_id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total paid returns
        $vars['totalPaidReturns'] = DB::table('bfo_returns')
            ->join('financial_months', 'financial_months.id', 'bfo_returns.financial_month_id')
            ->where('bfo_returns.status', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = DB::table('bfo_returns')
            ->join('businesses', 'businesses.id', 'bfo_returns.business_id')
            ->join('financial_months', 'financial_months.id', 'bfo_returns.financial_month_id')
            ->where('businesses.status', BusinessStatus::APPROVED)
            ->where('bfo_returns.status', '!=', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('bfo_returns')
            ->join('financial_months', 'bfo_returns.financial_month_id', 'financial_months.id')
            ->where('bfo_returns.created_at', '>', 'financial_months.due_date')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('bfo_returns')
            ->join('financial_months', 'bfo_returns.financial_month_id', 'financial_months.id')
            ->join('zm_bills', 'bfo_returns.id', 'zm_bills.billable_id')
            ->join('zm_payments', 'zm_payments.zm_bill_id', 'zm_bills.id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->where('zm_bills.billable_type', BfoReturn::class)
            ->where('bfo_returns.status', 'complete')
            ->where('bfo_returns.created_at', '>', 'zm_payments.trx_time')
            ->count();

        return view('returns.excise-duty.bfo.index', compact('vars', 'data'));
    }

    public function show($return_id)
    {
        $return = BfoReturn::query()->findOrFail(decrypt($return_id));
        return view('returns.excise-duty.bfo.show', compact('return', 'return_id'));
    }
}
