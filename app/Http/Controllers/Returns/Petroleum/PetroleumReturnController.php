<?php

namespace App\Http\Controllers\Returns\Petroleum;


use App\Http\Controllers\Controller;
use App\Models\Returns\Petroleum\PetroleumReturn;
use Illuminate\Http\Request;

class PetroleumReturnController extends Controller
{
    public function index()
    {
        return view('returns.petroleum.filing.index');
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
        return view('returns.filing.petroleum.filing.show', compact('return'));
    }

    public function edit($return)
    {
        return view('returns.petroleum.filing.edit', compact('return'));
    }
}
