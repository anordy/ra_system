<?php

namespace App\Http\Controllers\Returns\StampDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\StampDuty\StampDutyReturnPenalty;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;

class StampDutyReturnController extends Controller
{
    use ReturnCardReport, ReturnSummaryCardTrait;

    public function index()
    {

        $paidData = $this->returnCardReportForPaidReturns(StampDutyReturn::class, StampDutyReturn::getTableName(), StampDutyReturnPenalty::getTableName());

        $unpaidData = $this->returnCardReportForUnpaidReturns(StampDutyReturn::class, StampDutyReturn::getTableName(), StampDutyReturnPenalty::getTableName());

        $vars = $this->getSummaryData(StampDutyReturn::query());

        return view('returns.stamp-duty.index', compact('vars', 'paidData', 'unpaidData'));
    }

    public function show($returnId)
    {
        $returnId = decrypt($returnId);
        $return = StampDutyReturn::findOrFail($returnId);
        return view('returns.stamp-duty.show', compact('return'));
    }
}
