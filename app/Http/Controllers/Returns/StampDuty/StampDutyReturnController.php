<?php

namespace App\Http\Controllers\Returns\StampDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\StampDuty\StampDutyReturnPenalty;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Illuminate\Support\Facades\Gate;

class StampDutyReturnController extends Controller
{
    use ReturnCardReport, ReturnSummaryCardTrait;

    public function index()
    {
        if (!Gate::allows('return-stamp-duty-return-view')) {
            abort(403);
        }
        $paidData = $this->returnCardReportForPaidReturns(StampDutyReturn::class, StampDutyReturn::getTableName(), StampDutyReturnPenalty::getTableName());

        $unpaidData = $this->returnCardReportForUnpaidReturns(StampDutyReturn::class, StampDutyReturn::getTableName(), StampDutyReturnPenalty::getTableName());

        $vars = $this->getSummaryData(StampDutyReturn::query());

        return view('returns.stamp-duty.index', compact('vars', 'paidData', 'unpaidData'));
    }

    public function show($returnId)
    {
        if (!Gate::allows('return-stamp-duty-return-view')) {
            abort(403);
        }
        $returnId = decrypt($returnId);
        $return = StampDutyReturn::findOrFail($returnId);
        return view('returns.stamp-duty.show', compact('return'));
    }
}
