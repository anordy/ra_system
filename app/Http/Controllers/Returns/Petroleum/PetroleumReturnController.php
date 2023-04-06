<?php

namespace App\Http\Controllers\Returns\Petroleum;

use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\Petroleum\PetroleumPenalty;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Traits\ReturnSummaryCardTrait;
use App\Traits\ReturnCardReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PetroleumReturnController extends Controller
{
    public function index()
    {
        if (!Gate::allows('return-petroleum-return-view')) {
            abort(403);
        }

        $tableName = 'returns.petroleum.petroleum-return-table';
        $cardOne   = 'returns.petroleum.petroleum-card-one';
        $cardTwo   = 'returns.petroleum.petroleum-card-two';

        return view('returns.petroleum.filing.index', compact('tableName', 'cardOne', 'cardTwo'));
    }

    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return   = PetroleumReturn::findOrFail($returnId);
        $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');
        return view('returns.petroleum.filing.show', compact('return'));
    }

}
