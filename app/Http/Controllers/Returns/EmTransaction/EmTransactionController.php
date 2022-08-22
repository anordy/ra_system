<?php

namespace App\Http\Controllers\Returns\EmTransaction;

use App\Http\Controllers\Controller;
use App\Models\Returns\EmTransactionPenalty;
use App\Models\Returns\EmTransactionReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;

class EmTransactionController extends Controller
{
    use ReturnCardReport,ReturnSummaryCardTrait;

    public function index()
    {
        $paidData = $this->returnCardReportForPaidReturns(EmTransactionReturn::class, EmTransactionReturn::getTableName(), EmTransactionPenalty::getTableName());

        $unpaidData = $this->returnCardReportForUnpaidReturns(EmTransactionReturn::class, EmTransactionReturn::getTableName(), EmTransactionPenalty::getTableName());

        $vars = $this->getSummaryData(EmTransactionReturn::query());

        return view('returns.em-transaction.index', compact('vars', 'paidData', 'unpaidData'));
    }

    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return = EmTransactionReturn::findOrFail($returnId);
        return view('returns.em-transaction.show', compact('return', 'returnId'));
    }
}
