<?php

namespace App\Http\Controllers\Returns\BfoExciseDuty;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\Returns\BFO\BfoReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class BfoExciseDutyController extends Controller
{
    use ReturnCardReport, ReturnSummaryCardTrait;

    public function index()
    {
        if (!Gate::allows('return-bfo-excise-duty-return-view')) {
            abort(403);
        }

        try {
            $cardOne = 'returns.bfo-excise-duty.bfo-card-one';
            $cardTwo = 'returns.bfo-excise-duty.bfo-card-two';
            $tableName = 'returns.bfo-excise-duty.bfo-excise-duty-table';
            return view('returns.excise-duty.bfo.index', compact('cardOne', 'cardTwo', 'tableName'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-BFO-RETURN-CONTROLLER-INDEX', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }

    }

    public function show($return_id)
    {
        if (!Gate::allows('return-bfo-excise-duty-return-view')) {
            abort(403);
        }

        try {
            $return = BfoReturn::with(['penalties'])->findOrFail(decrypt($return_id), ['id', 'business_location_id', 'business_id', 'filed_by_type', 'filed_by_id', 'tax_type_id', 'financial_year_id', 'edited_count', 'status', 'application_status', 'return_category', 'currency', 'financial_month_id', 'total_amount_due', 'total_amount_due_with_penalties', 'penalty', 'interest', 'filing_due_date', 'payment_due_date', 'submitted_at', 'paid_at', 'vetting_status']);
            $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');
            return view('returns.excise-duty.bfo.show', compact('return', 'return_id'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-BFO-RETURN-CONTROLLER-SHOW', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }
}
