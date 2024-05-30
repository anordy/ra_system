<?php

namespace App\Http\Controllers\Returns\LumpSum;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\Returns\LumpSum\LumpSumReturn;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class LumpSumReturnController extends Controller
{
    public function index()
    {
        if (!Gate::allows('return-lump-sum-payment-return-view')) {
            abort(403);
        }

        try {
            $tableName = 'returns.lump-sum.lump-sum-returns-table';
            $cardOne   = 'returns.lump-sum.lump-sum-card-one';
            $cardTwo   = 'returns.lump-sum.lump-sum-card-two';

            return view('returns.lump-sum.history', compact('tableName', 'cardOne', 'cardTwo'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-LUMPSUM-RETURN-CONTROLLER-INDEX', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }

    }
 
    public function view($row)
    {
        if (!Gate::allows('return-lump-sum-payment-return-view')) {
            abort(403);
        }

        try {
            $id = decrypt($row);
            $return = LumpSumReturn::findOrFail($id, ['id', 'filed_by_id', 'filed_by_type', 'tax_type_id', 'business_id', 'business_location_id', 'financial_month_id', 'financial_year_id', 'total_amount_due', 'total_amount_due_with_penalties', 'quarter', 'installment', 'quarter_name', 'currency', 'amount', 'edited_count', 'control_no', 'penalty', 'interest', 'filing_due_date', 'payment_due_date', 'submitted_at', 'paid_at', 'status', 'application_status', 'return_category', 'created_at', 'updated_at']);
            return view('returns.lump-sum.view', compact('return'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-LUMPSUM-RETURN-CONTROLLER-VIEW', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }

    }
}
