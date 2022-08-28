<?php

namespace App\Http\Controllers\Returns\BfoExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\BFO\BfoPenalty;
use App\Models\Returns\BFO\BfoReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class BfoExciseDutyController extends Controller
{
    use ReturnCardReport,ReturnSummaryCardTrait;

    public function index()
    {
        if (!Gate::allows('return-bfo-excise-duty-return-view')) {
            abort(403);
        }
        $paidData = $this->returnCardReportForPaidReturns(BfoReturn::class, BfoReturn::getTableName(), BfoPenalty::getTableName());

        $unpaidData = $this->returnCardReportForUnpaidReturns(BfoReturn::class, BfoReturn::getTableName(), BfoPenalty::getTableName());

        $vars = $this->getSummaryData(BfoReturn::query());

        return view('returns.excise-duty.bfo.index', compact('vars', 'paidData', 'unpaidData'));
    }

    public function show($return_id)
    {
        $return = BfoReturn::query()->findOrFail(decrypt($return_id));
        return view('returns.excise-duty.bfo.show', compact('return', 'return_id'));
    }
}
