<?php

namespace App\Http\Controllers\Returns\ExciseDuty;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class MnoReturnController extends Controller
{
    use ReturnCardReport, ReturnSummaryCardTrait;

    public function index()
    {
        if (!Gate::allows('return-mno-excise-duty-return-view')) {
            abort(403);
        }

        try {
            $cardOne = 'returns.excise-duty.mno-card-one';
            $cardTwo = 'returns.excise-duty.mno-card-two';
            $tableName = 'returns.excise-duty.mno-returns-table';
            return view('returns.excise-duty.mno.index', compact('cardTwo', 'cardOne', 'tableName'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-MNO-RETURN-CONTROLLER-INDEX', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function show($id)
    {
        if (!Gate::allows('return-mno-excise-duty-return-view')) {
            abort(403);
        }

        try {
            $return = MnoReturn::with(['penalties'])->findOrFail(decrypt($id), ['id', 'business_id', 'business_location_id', 'filed_by_id', 'filed_by_type', 'financial_year_id', 'financial_month_id', 'tax_type_id', 'total_amount_due', 'total_amount_due_with_penalties', 'currency', 'penalty', 'interest', 'filing_due_date', 'payment_due_date', 'submitted_at', 'paid_at', 'status', 'application_status', 'return_category', 'vetting_status', 'edited_count']);
            $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');
            return view('returns.excise-duty.mno.show', compact('return'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-MNO-RETURN-CONTROLLER-SHOW', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

}
