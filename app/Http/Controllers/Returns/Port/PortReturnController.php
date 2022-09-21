<?php

namespace App\Http\Controllers\Returns\Port;

use App\Http\Controllers\Controller;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\Port\PortReturnPenalty;
use App\Traits\PortReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Illuminate\Support\Facades\Gate;

class PortReturnController extends Controller
{
    use PortReturnCardReport, ReturnSummaryCardTrait;

    public function airport()
    {
        if (!Gate::allows('return-port-return-view')) {
            abort(403);
        }

        $paidData = $this->returnCardReportForPaidReturns(PortReturn::class, PortReturn::getTableName(), PortReturnPenalty::getTableName());

        $unpaidData = $this->returnCardReportForUnpaidReturns(PortReturn::class, PortReturn::getTableName(), PortReturnPenalty::getTableName());

        $vars      = $this->getSummaryData(PortReturn::query());
        $tableName = 'returns.port.port-return-table';

        return view('returns.port.airport', compact('vars', 'paidData', 'unpaidData', 'tableName'));
    }

        public function seaport()
    {
        if (!Gate::allows('return-port-return-view')) {
            abort(403);
        }

        $cardOne   = 'returns.port.port-card-one';
        $cardTwo   = 'returns.port.port-card-two';
        $tableName = 'returns.port.port-return-table';

        return view('returns.port.seaport', compact('cardOne', 'cardTwo', 'tableName'));
    }

    public function show($return_id)
    {
        if (!Gate::allows('return-port-return-view')) {
            abort(403);
        }

        $returnId = decrypt($return_id);
        $return   = PortReturn::findOrFail($returnId);

        return view('returns.port.show', compact('return'));
    }
}
