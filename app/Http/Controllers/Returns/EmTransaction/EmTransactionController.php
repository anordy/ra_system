<?php

namespace App\Http\Controllers\Returns\EmTransaction;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\Returns\EmTransactionReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class EmTransactionController extends Controller
{
    use ReturnCardReport, ReturnSummaryCardTrait;

    public function index()
    {
        if (!Gate::allows('return-electronic-money-transaction-return-view')) {
            abort(403);
        }

        try {
            $cardOne = 'returns.em-transaction.em-card-one';
            $cardTwo = 'returns.em-transaction.em-card-two';
            $tableName = 'returns.em-transaction.em-transactions-table';
            return view('returns.em-transaction.index', compact('cardTwo', 'cardOne', 'tableName'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-EM-TRANSACTION-CONTROLLER-INDEX', [$exception]);
            session()->flash('error', CustomMessage::error());
            return redirect()->back();
        }

    }

    public function show($return_id)
    {
        if (!Gate::allows('return-electronic-money-transaction-return-view')) {
            abort(403);
        }

        try {
            $returnId = decrypt($return_id);
            $return = EmTransactionReturn::with(['penalties'])->findOrFail($returnId, ['id', 'business_location_id', 'business_id', 'filed_by_type', 'filed_by_id', 'tax_type_id', 'financial_year_id', 'edited_count', 'currency', 'financial_month_id', 'total_amount_due', 'total_amount_due_with_penalties', 'penalty', 'interest', 'filing_due_date', 'payment_due_date', 'submitted_at', 'paid_at', 'status', 'application_status', 'return_category', 'vetting_status']);
            $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');
            return view('returns.em-transaction.show', compact('return', 'returnId'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-EM-TRANSACTION-CONTROLLER-SHOW', [$exception]);
            session()->flash('error', CustomMessage::error());
            return redirect()->back();
        }
    }
}
