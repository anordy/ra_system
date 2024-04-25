<?php

namespace App\Http\Controllers\Taxpayers;

use App\Http\Controllers\Controller;
use App\Models\Taxpayer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

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

        try {
            $taxPayer = Taxpayer::with('region:id,name', 'district:id,name', 'ward:id,name', 'street:id,name')->findOrFail(decrypt($taxPayerId));
            return view('taxpayers.show', compact('taxPayer'));
        } catch (\Exception $e) {
            Log::error($e);
            session()->flash('error', 'Something went wrong, please contact the administrator for help');
            return redirect()->back();
        }
    }
}
