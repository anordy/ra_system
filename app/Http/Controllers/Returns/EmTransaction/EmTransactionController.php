<?php

namespace App\Http\Controllers\Returns\EmTransaction;

use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\EmTransactionReturn;
use App\Traits\ReturnCardReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmTransactionController extends Controller
{
    use ReturnCardReport;

    public function index()
    {
        $data = $this->returnCardReport(EmTransactionReturn::class, 'em_transaction', 'em_transaction');

        //last day of last month
        $from = Carbon::now()->subMonth()->lastOfMonth()->toDateTimeString();

        //fist day of next month
        $to = Carbon::now()->addMonth()->firstOfMonth()->toDateTimeString();

        //total submitted returns
        $vars['totalSubmittedReturns'] = DB::table('em_transaction_returns')
            ->join('financial_months', 'financial_months.id', 'em_transaction_returns.financial_month_id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total paid returns
        $vars['totalPaidReturns'] = DB::table('em_transaction_returns')
            ->join('financial_months', 'financial_months.id', 'em_transaction_returns.financial_month_id')
            ->where('em_transaction_returns.status', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = DB::table('em_transaction_returns')
            ->join('businesses', 'businesses.id', 'em_transaction_returns.business_id')
            ->join('financial_months', 'financial_months.id', 'em_transaction_returns.financial_month_id')
            ->where('businesses.status', BusinessStatus::APPROVED)
            ->where('em_transaction_returns.status', '!=', 'complete')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('em_transaction_returns')
            ->join('financial_months', 'em_transaction_returns.financial_month_id', 'financial_months.id')
            ->where('em_transaction_returns.created_at', '>', 'financial_months.due_date')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('em_transaction_returns')
            ->join('financial_months', 'em_transaction_returns.financial_month_id', 'financial_months.id')
            ->join('zm_bills', 'em_transaction_returns.id', 'zm_bills.billable_id')
            ->join('zm_payments', 'zm_payments.zm_bill_id', 'zm_bills.id')
            ->whereBetween('financial_months.due_date', [$from, $to])
            ->where('zm_bills.billable_type', EmTransactionReturn::class)
            ->where('em_transaction_returns.status', 'complete')
            ->where('em_transaction_returns.created_at', '>', 'zm_payments.trx_time')
            ->count();
        return view('returns.em-transaction.index', compact('vars', 'data'));
    }

    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return = EmTransactionReturn::findOrFail($returnId);
        return view('returns.em-transaction.show', compact('return', 'returnId'));
    }
}
