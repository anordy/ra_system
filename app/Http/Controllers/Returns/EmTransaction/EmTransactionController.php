<?php

namespace App\Http\Controllers\Returns\EmTransaction;

use App\Http\Controllers\Controller;
use App\Models\Returns\EmTransactionReturn;
use App\Traits\ReturnCardReport;
use Illuminate\Support\Facades\DB;

class EmTransactionController extends Controller
{
    use ReturnCardReport;

    public function index(){
        $data = $this->returnCardReport(EmTransactionReturn::class, 'em_transaction', 'em_transaction');

        $vars['totalSubmittedReturns'] = EmTransactionReturn::query()->whereNotNull('created_at')->count();

        //total paid returns
        $vars['totalPaidReturns'] = EmTransactionReturn::where('status', 'complete')->count();

        //total unpaid returns
        $vars['totalUnpaidReturns'] = EmTransactionReturn::where('status', '!=', 'complete')->count();

        //late filed returns
        $vars['totalLateFiledReturns'] = DB::table('em_transaction_returns')
            ->join('financial_months', 'em_transaction_returns.financial_month_id', 'financial_months.id')
            ->where('em_transaction_returns.created_at', '>', 'financial_months.due_date')
            ->count();

        //total late paid returns
        $vars['totalLatePaidReturns'] = DB::table('em_transaction_returns')
            ->join('zm_bills', 'em_transaction_returns.id', 'zm_bills.billable_id')
            ->join('zm_payments', 'zm_payments.zm_bill_id', 'zm_bills.id')
            ->where('zm_bills.billable_type', EmTransactionReturn::class)
            ->where('em_transaction_returns.status', 'complete')
            ->where('em_transaction_returns.created_at', '>', 'zm_payments.trx_time')
            ->count();
        return view('returns.em-transaction.index',compact('vars','data'));
    }

    public function show($return_id){
        $returnId = decrypt($return_id);
        $return = EmTransactionReturn::findOrFail($returnId);
        return view('returns.em-transaction.show', compact('return', 'returnId'));
    }

}
