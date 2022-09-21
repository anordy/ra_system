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

        $cardOne   = 'returns.em-transaction.em-card-one';
        $cardTwo   = 'returns.em-transaction.em-card-two';
        $tableName ='returns.em-transaction.em-transactions-table';

        return view('returns.em-transaction.index', compact('cardTwo', 'cardOne', 'tableName'));
    }

    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return   = EmTransactionReturn::findOrFail($returnId);

        return view('returns.em-transaction.show', compact('return', 'returnId'));
    }
}
