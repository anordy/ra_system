<?php

namespace App\Http\Controllers\Returns\StampDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;

class StampDutyReturnController extends Controller
{
    use ReturnCardReport, ReturnSummaryCardTrait;

    public function index()
    {

        $data = $this->returnCardReport(StampDutyReturn::class, 'stamp_duty', 'stamp_duty_return');

        $vars = $this->getSummaryData(StampDutyReturn::query());

        return view('returns.stamp-duty.index', compact('vars', 'data'));
    }

    public function show($returnId)
    {
        $returnId = decrypt($returnId);
        $return = StampDutyReturn::findOrFail($returnId);
        return view('returns.stamp-duty.show', compact('return'));
    }
}
