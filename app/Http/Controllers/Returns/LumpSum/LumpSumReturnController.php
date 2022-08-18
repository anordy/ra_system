<?php

namespace App\Http\Controllers\Returns\LumpSum;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Returns\LumpSum\LumpSumReturns;
use App\Models\BusinessLocation;
use App\Models\BusinessStatus;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Traits\ReturnCardReport;
use App\Traits\ReturnSummaryCardTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LumpSumReturnController extends Controller
{
    use ReturnCardReport,ReturnSummaryCardTrait;

    public function index()
    {
        $data = $this->returnCardReport(LumpSumReturn::class, 'lump_sum', 'lump_sum');

        $vars = $this->getSummaryData(LumpSumReturn::query());

        return view('returns.lump-sum.history', compact('vars', 'data'));
    }

    public function history()
    {
        return view('returns.lump-sum.history');
    }

    public function view($row)
    {
        $row = decrypt($row);
        $id  = $row->id;

        $return = LumpSumReturn::findOrFail($id);

        return view('returns.lump-sum.view', compact('return'));
    }
}
