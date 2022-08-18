<?php

namespace App\Http\Controllers\Returns\Petroleum;


use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Traits\ReturnCardReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetroleumReturnController extends Controller
{
    use ReturnCardReport;

    public function index()
    {
        //last day of last month
        $from = Carbon::now()->subMonth()->lastOfMonth()->toDateTimeString();

        //fist day of next month
        $to = Carbon::now()->addMonth()->firstOfMonth()->toDateTimeString();

        //total submitted returns
        $vars['totalSubmittedReturns'] = DB::table('petroleum_returns')
            ->join('financial_months', 'financial_months.id', 'petroleum_returns.financial_month_id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total paid returns
        $vars['totalPaidReturns'] = DB::table('petroleum_returns')
            ->join('financial_months', 'financial_months.id', 'petroleum_returns.financial_month_id')
            ->where('petroleum_returns.status', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = DB::table('petroleum_returns')
            ->join('businesses', 'businesses.id', 'petroleum_returns.business_id')
            ->join('financial_months', 'financial_months.id', 'petroleum_returns.financial_month_id')
            ->where('businesses.status', BusinessStatus::APPROVED)
            ->where('petroleum_returns.status', '!=', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('petroleum_returns')
            ->join('financial_months', 'petroleum_returns.financial_month_id', 'financial_months.id')
            ->where('petroleum_returns.created_at', '>', 'financial_months.due_date')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('petroleum_returns')
            ->join('financial_months', 'petroleum_returns.financial_month_id', 'financial_months.id')
            ->join('zm_bills', 'petroleum_returns.id', 'zm_bills.billable_id')
            ->join('zm_payments', 'zm_payments.zm_bill_id', 'zm_bills.id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->where('zm_bills.billable_type',PetroleumReturn::class)
            ->where('petroleum_returns.status', 'complete')
            ->where('petroleum_returns.created_at', '>', 'zm_payments.trx_time')
            ->count();

        $data = $this->returnCardReport(PetroleumReturn::class, 'petroleum', 'petroleum');

        return view('returns.petroleum.filing.index', compact('vars', 'data'));
    }

    public function create(Request $request)
    {
        $location = $request->location;
        $tax_type = $request->tax_type;
        $business = $request->business;
        return view('returns.petroleum.filing.filing', compact('location', 'tax_type', 'business'));
    }


    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return = PetroleumReturn::findOrFail($returnId);
        return view('returns.petroleum.filing.show', compact('return'));
    }

    public function edit($return)
    {
        return view('returns.petroleum.filing.edit', compact('return'));
    }
}
