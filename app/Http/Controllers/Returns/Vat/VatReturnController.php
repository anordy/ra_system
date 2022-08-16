<?php

namespace App\Http\Controllers\Returns\Vat;

use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\Vat\VatReturn;
use App\Traits\ReturnCardReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VatReturnController extends Controller
{
    use ReturnCardReport;

    public function index()
    {
        $data = $this->returnCardReport(VatReturn::class, 'vat', 'vat_return');

        //last day of last month
        $from = Carbon::now()->subMonth()->lastOfMonth()->toDateTimeString();

        //fist day of next month
        $to = Carbon::now()->addMonth()->firstOfMonth()->toDateTimeString();

        //total submitted returns
        $vars['totalSubmittedReturns'] = DB::table('vat_returns')
            ->join('financial_months', 'financial_months.id', 'vat_returns.financial_month_id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total paid returns
        $vars['totalPaidReturns'] = DB::table('vat_returns')
            ->join('financial_months', 'financial_months.id', 'vat_returns.financial_month_id')
            ->where('vat_returns.status', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = DB::table('vat_returns')
            ->join('businesses', 'businesses.id', 'vat_returns.business_id')
            ->join('financial_months', 'financial_months.id', 'vat_returns.financial_month_id')
            ->where('businesses.status', BusinessStatus::APPROVED)
            ->where('vat_returns.status', '!=', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('vat_returns')
            ->join('financial_months', 'vat_returns.financial_month_id', 'financial_months.id')
            ->where('vat_returns.created_at', '>', 'financial_months.due_date')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('vat_returns')
            ->join('financial_months', 'vat_returns.financial_month_id', 'financial_months.id')
            ->join('zm_bills', 'vat_returns.id', 'zm_bills.billable_id')
            ->join('zm_payments', 'zm_payments.zm_bill_id', 'zm_bills.id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->where('zm_bills.billable_type', VatReturn::class)
            ->where('vat_returns.status', 'complete')
            ->where('vat_returns.created_at', '>', 'zm_payments.trx_time')
            ->count(); //last day of last month
        $from = Carbon::now()->subMonth()->lastOfMonth()->toDateTimeString();

        //fist day of next month
        $to = Carbon::now()->addMonth()->firstOfMonth()->toDateTimeString();

        //total submitted returns
        $vars['totalSubmittedReturns'] = DB::table('vat_returns')
            ->join('financial_months', 'financial_months.id', 'vat_returns.financial_month_id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total paid returns
        $vars['totalPaidReturns'] = DB::table('vat_returns')
            ->join('financial_months', 'financial_months.id', 'vat_returns.financial_month_id')
            ->where('vat_returns.status', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = DB::table('vat_returns')
            ->join('businesses', 'businesses.id', 'vat_returns.business_id')
            ->join('financial_months', 'financial_months.id', 'vat_returns.financial_month_id')
            ->where('businesses.status', BusinessStatus::APPROVED)
            ->where('vat_returns.status', '!=', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('vat_returns')
            ->join('financial_months', 'vat_returns.financial_month_id', 'financial_months.id')
            ->where('vat_returns.created_at', '>', 'financial_months.due_date')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('vat_returns')
            ->join('financial_months', 'vat_returns.financial_month_id', 'financial_months.id')
            ->join('zm_bills', 'vat_returns.id', 'zm_bills.billable_id')
            ->join('zm_payments', 'zm_payments.zm_bill_id', 'zm_bills.id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->where('zm_bills.billable_type', VatReturn::class)
            ->where('vat_returns.status', 'complete')
            ->where('vat_returns.created_at', '>', 'zm_payments.trx_time')
            ->count();

        return view('returns.vat_returns.index', compact('vars', 'data'));
    }
    public function show($id)
    {
        $return = VatReturn::query()->findOrFail(decrypt($id));
        return view('returns.vat_returns.show', compact('return', 'id'));
    }
}
