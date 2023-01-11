<?php

namespace App\Http\Controllers\Taxpayers;

use App\Http\Controllers\Controller;
use App\Models\Taxpayer;
use Illuminate\Support\Facades\Gate;

class TaxpayersController extends Controller
{
    public function index(){
        if (!Gate::allows('taxpayer_view')) {
            abort(403);
        }
        return view('taxpayers.index');
    }

    public function show($taxPayerId){
        if (!Gate::allows('taxpayer_view')) {
            abort(403);
        }
        $taxPayer = Taxpayer::with('region:id,name', 'district:id,name', 'ward:id,name', 'street:id,name')->findOrFail(decrypt($taxPayerId));
        return view('taxpayers.show', compact('taxPayer'));
    }
}
