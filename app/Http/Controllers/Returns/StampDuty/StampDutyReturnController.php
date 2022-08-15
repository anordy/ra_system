<?php

namespace App\Http\Controllers\Returns\StampDuty;

use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Traits\ReturnCardReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StampDutyReturnController extends Controller
{
    use ReturnCardReport;

    public function index()
    {

        $data = $this->returnCardReport(StampDutyReturn::class, 'stamp_duty', 'stamp_duty_return');

        //last day of last month
        $from = Carbon::now()->subMonth()->lastOfMonth()->toDateTimeString();

        //fist day of next month
        $to = Carbon::now()->addMonth()->firstOfMonth()->toDateTimeString();

        //total submitted returns
        $vars['totalSubmittedReturns'] = DB::table('stamp_duty_returns')
            ->join('financial_months', 'financial_months.id', 'stamp_duty_returns.financial_month_id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total paid returns
        $vars['totalPaidReturns'] = DB::table('stamp_duty_returns')
            ->join('financial_months', 'financial_months.id', 'stamp_duty_returns.financial_month_id')
            ->where('stamp_duty_returns.status', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = DB::table('stamp_duty_returns')
            ->join('businesses', 'businesses.id', 'stamp_duty_returns.business_id')
            ->join('financial_months', 'financial_months.id', 'stamp_duty_returns.financial_month_id')
            ->where('businesses.status', BusinessStatus::APPROVED)
            ->where('stamp_duty_returns.status', '!=', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('stamp_duty_returns')
            ->join('financial_months', 'stamp_duty_returns.financial_month_id', 'financial_months.id')
            ->where('stamp_duty_returns.created_at', '>', 'financial_months.due_date')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('stamp_duty_returns')
            ->join('financial_months', 'stamp_duty_returns.financial_month_id', 'financial_months.id')
            ->join('zm_bills', 'stamp_duty_returns.id', 'zm_bills.billable_id')
            ->join('zm_payments', 'zm_payments.zm_bill_id', 'zm_bills.id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->where('zm_bills.billable_type', StampDutyReturn::class)
            ->where('stamp_duty_returns.status', 'complete')
            ->where('stamp_duty_returns.created_at', '>', 'zm_payments.trx_time')
            ->count();

        return view('returns.stamp-duty.index', compact('vars', 'data'));
    }

    public function show($returnId)
    {
        $returnId = decrypt($returnId);
        $return = StampDutyReturn::findOrFail($returnId);
        return view('returns.stamp-duty.show', compact('return'));
    }
}
