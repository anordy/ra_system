<?php

namespace App\Http\Controllers\Returns\EmTransaction;

use App\Http\Controllers\Controller;
use App\Models\Returns\EmTransactionPenalty;
use App\Models\Returns\EmTransactionReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Illuminate\Support\Facades\Gate;

class EmTransactionController extends Controller
{
    use ReturnCardReport,ReturnSummaryCardTrait;

    public function index()
    {
        if (!Gate::allows('return-electronic-money-transaction-return-view')) {
            abort(403);
        }

        $paidData = $this->returnCardReportForPaidReturns(EmTransactionReturn::class, EmTransactionReturn::getTableName(), EmTransactionPenalty::getTableName());

        $unpaidData = $this->returnCardReportForUnpaidReturns(EmTransactionReturn::class, EmTransactionReturn::getTableName(), EmTransactionPenalty::getTableName());

        $vars          = $this->getSummaryData(EmTransactionReturn::query());
        $tableName     ='returns.em-transaction.em-transactions-table';

        return view('returns.em-transaction.index', compact('vars', 'paidData', 'unpaidData', 'tableName'));
    }

    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return   = EmTransactionReturn::findOrFail($returnId);

        return view('returns.em-transaction.show', compact('return', 'returnId'));
    }
}
