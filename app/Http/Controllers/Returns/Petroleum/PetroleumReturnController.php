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

    public function create(Request $request)
    {
        $location = $request->location;
        $tax_type = $request->tax_type;
        $business = $request->business;

        return view('returns.petroleum.filing.filing', compact('location', 'tax_type', 'business'));
    }

    public function show($return_id)
    {
        $returnId = decrypt($return_id);
        $return   = PetroleumReturn::findOrFail($returnId);
        $return->penalties = $return->penalties->merge($return->tax_return->penalties);
        return view('returns.petroleum.filing.show', compact('return'));
    }

    public function edit($return)
    {
        return view('returns.petroleum.filing.edit', compact('return'));
    }
}
