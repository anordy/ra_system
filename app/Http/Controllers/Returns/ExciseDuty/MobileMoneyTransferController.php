<?php

namespace App\Http\Controllers\Returns\ExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\MmTransferPenalty;
use App\Models\Returns\MmTransferReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MobileMoneyTransferController extends Controller
{
    use ReturnCardReport, ReturnSummaryCardTrait;

    public function index()
    {
        if (!Gate::allows('return-mobile-money-transfer-view')) {
            abort(403);
        }
        $paidData = $this->returnCardReportForPaidReturns(MmTransferReturn::class, MmTransferReturn::getTableName(), MmTransferPenalty::getTableName());

        $unpaidData = $this->returnCardReportForUnpaidReturns(MmTransferReturn::class, MmTransferReturn::getTableName(), MmTransferPenalty::getTableName());

        $vars = $this->getSummaryData(MmTransferReturn::query());

        return view('returns.excise-duty.mobile-money-transfer.index', compact( 'vars', 'paidData', 'unpaidData'));
    }

    public function show($return_id)
    {
        $return = MmTransferReturn::query()->findOrFail(decrypt($return_id));
        return view('returns.excise-duty.mobile-money-transfer.show', compact('return','return_id'));
    }
}
