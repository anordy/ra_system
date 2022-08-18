<?php

namespace App\Http\Controllers\Returns\EmTransaction;

use App\Http\Controllers\Controller;
use App\Models\Returns\EmTransactionReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;

class EmTransactionController extends Controller
{
    use ReturnCardReport,ReturnSummaryCardTrait;

    public function index()
    {
        $data = $this->returnCardReport(EmTransactionReturn::class, 'em_transaction', 'em_transaction');

        $vars = $this->getSummaryData(EmTransactionReturn::query());

        return view('returns.em-transaction.index', compact('vars', 'data'));
    }

    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return = EmTransactionReturn::findOrFail($returnId);
        return view('returns.em-transaction.show', compact('return', 'returnId'));
    }
}
