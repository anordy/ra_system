<?php

namespace App\Http\Controllers\Returns\LumpSum;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Returns\LumpSum\LumpSumReturns;
use App\Models\BusinessLocation;
use App\Models\BusinessStatus;
use App\Models\Returns\LumpSum\LumpSumPenalties;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class LumpSumReturnController extends Controller
{
    use ReturnCardReport,ReturnSummaryCardTrait;

    public function index()
    {
        if (!Gate::allows('return-lump-sum-payment-return-view')) {
            abort(403);
        }

        $paidData = $this->returnCardReportForPaidReturns(LumpSumReturn::class, LumpSumReturn::getTableName(), LumpSumPenalties::getTableName());

        $unpaidData = $this->returnCardReportForUnpaidReturns(LumpSumReturn::class, LumpSumReturn::getTableName(), LumpSumPenalties::getTableName());

        $vars = $this->getSummaryData(LumpSumReturn::query());

        $tableName = 'returns.lump-sum.lump-sum-returns-table';

        return view('returns.lump-sum.history', compact('vars', 'paidData', 'unpaidData', 'tableName'));
    }

    public function view($row)
    {
        if (!Gate::allows('return-lump-sum-payment-return-view')) {
            abort(403);
        }
        $id = decrypt($row);
       
        $return = LumpSumReturn::findOrFail($id);

        return view('returns.lump-sum.view', compact('return'));
    }
}
