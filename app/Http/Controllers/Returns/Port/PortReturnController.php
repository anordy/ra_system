<?php

namespace App\Http\Controllers\Returns\Port;

use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\Port\PortReturn;
use App\Traits\ReturnCardReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PortReturnController extends Controller
{
    use ReturnCardReport;

    public function index()
    {
        //last day of last month
        $from = Carbon::now()->subMonth()->lastOfMonth()->toDateTimeString();

        //fist day of next month
        $to = Carbon::now()->addMonth()->firstOfMonth()->toDateTimeString();

        //total submitted returns
        $vars['totalSubmittedReturns'] = DB::table('port_returns')
            ->join('financial_months', 'financial_months.id', 'port_returns.financial_month_id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total paid returns
        $vars['totalPaidReturns'] = DB::table('port_returns')
            ->join('financial_months', 'financial_months.id', 'port_returns.financial_month_id')
            ->where('port_returns.status', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = DB::table('port_returns')
            ->join('businesses', 'businesses.id', 'port_returns.business_id')
            ->join('financial_months', 'financial_months.id', 'port_returns.financial_month_id')
            ->where('businesses.status', BusinessStatus::APPROVED)
            ->where('port_returns.status', '!=', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('port_returns')
            ->join('financial_months', 'port_returns.financial_month_id', 'financial_months.id')
            ->where('port_returns.created_at', '>', 'financial_months.due_date')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('port_returns')
            ->join('financial_months', 'port_returns.financial_month_id', 'financial_months.id')
            ->join('zm_bills', 'port_returns.id', 'zm_bills.billable_id')
            ->join('zm_payments', 'zm_payments.zm_bill_id', 'zm_bills.id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->where('zm_bills.billable_type', PortReturn::class)
            ->where('port_returns.status', 'complete')
            ->where('port_returns.created_at', '>', 'zm_payments.trx_time')
            ->count();

        $returnQuery = PortReturn::where('status', '!=', 'complete');

        $data = [
            'totalTaxAmountTZS' => $returnQuery->sum('port_returns.total_amount_due_with_penalties_tzs'),
            'totalLateFilingTZS' => $penaltyDataTZS->sum('totalLateFiling'),
            'totalLatePaymentTZS' => $penaltyDataTZS->sum('totalLatePayment'),
            'totalRateTZS' => $penaltyDataTZS->sum('totalRate'),
            'totalTaxAmountUSD' => $returnQuery->sum('port_returns.total_amount_due_with_penalties_usd'),
            'totalLateFilingUSD' => $penaltyDataUSD->sum('totalLateFiling'),
            'totalLatePaymentUSD' => $penaltyDataUSD->sum('totalLatePayment'),
            'totalRateUSD' => $penaltyDataUSD->sum('totalRate'),
        ];
        // return $data;

        return view('returns.port.index', compact('vars', 'data'));
    }

    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return = PortReturn::findOrFail($returnId);
        return view('returns.port.show', compact('return'));
    }
}
