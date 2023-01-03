<?php

namespace App\Http\Controllers\Taxpayers;

use App\Http\Controllers\Controller;
use App\Models\TaxpayerAmendmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AmendmentRequestController extends Controller
{
    //
    public function index(){
        if (!Gate::allows('taxpayer-amendment-requests-view')) {
            abort(403);
        }
        return view('taxpayers.amendments.index');
    }

    public function show($id){
        if (!Gate::allows('taxpayer-amendment-request-view')) {
            abort(403);
        }

        return view('taxpayers.amendments.show', compact('id'));
    }
}
