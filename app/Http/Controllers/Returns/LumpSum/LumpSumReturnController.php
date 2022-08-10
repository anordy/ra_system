<?php

namespace App\Http\Controllers\Returns\LumpSum;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Returns\LumpSum\LumpSumReturns;
use App\Models\BusinessLocation;
use App\Models\Returns\LampSum\LampSumReturn;
use Illuminate\Http\Request;

class LumpSumReturnController extends Controller
{
    public function index()
    {
        return view('returns.lumpsum.history');
    }

    public function create(Request $request)
    {
        $location         = $request->location_id;
        $tax_type         = $request->tax_type_code;
        $business         = $request->business;
        $filling_month_id = $request->filling_month_id;
        $location         = BusinessLocation::findOrFail(decrypt($location));

        return view('returns.lump-sum.lump-sum', compact('location', 'tax_type', 'business', 'filling_month_id'));
    }
    
    public function history()
    {
        return view('returns.lumpsum.history');
    }

    public function view($row)
    {
        $row = decrypt($row);
        $id  = $row->id;
        
        $return = LampSumReturn::findOrFail($id);
        
        return view('returns.lumpsum.view', compact('return'));
    }
}
