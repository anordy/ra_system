<?php

namespace App\Http\Controllers\Returns\Petroleum;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\Returns\Petroleum\PetroleumReturn;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PetroleumReturnController extends Controller
{
    public function index()
    {
        if (!Gate::allows('return-petroleum-return-view')) {
            abort(403);
        }

        try {
            $tableName = 'returns.petroleum.petroleum-return-table';
            $cardOne = 'returns.petroleum.petroleum-card-one';
            $cardTwo = 'returns.petroleum.petroleum-card-two';
            return view('returns.petroleum.filing.index', compact('tableName', 'cardOne', 'cardTwo'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-PETROLEUM-RETURN-CONTROLLER-INDEX', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function show($return_id)
    {
        try {
            $returnId = decrypt($return_id);
            $return = PetroleumReturn::with(['penalties'])->findOrFail($returnId, ['id', 'business_id', 'business_location_id', 'filed_by_type', 'certificate_id', 'filed_by_id', 'tax_type_id', 'financial_year_id', 'financial_month_id', 'currency', 'total_amount_due', 'total_amount_due_with_penalties', 'petroleum_levy', 'infrastructure_tax', 'rdf_tax', 'road_lincence_fee', 'status', 'penalty', 'interest', 'filing_due_date', 'payment_due_date', 'submitted_at', 'paid_at', 'application_status', 'return_category', 'edited_count', 'created_at', 'updated_at', 'vetting_status']);
            $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');
            return view('returns.petroleum.filing.show', compact('return'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-PETROLEUM-RETURN-CONTROLLER-SHOW', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

}
