<?php

namespace App\Http\Controllers\Returns\Petroleum;


use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Traits\ReturnSummaryCardTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetroleumReturnController extends Controller
{
    use ReturnSummaryCardTrait;
    
    public function index()
    {
        $vars = $this->getSummaryData(PetroleumReturn::query());

        return view('returns.petroleum.filing.index', compact('vars'));
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
        $return = PetroleumReturn::findOrFail($returnId);
        return view('returns.petroleum.filing.show', compact('return'));
    }

    public function edit($return)
    {
        return view('returns.petroleum.filing.edit', compact('return'));
    }
}
