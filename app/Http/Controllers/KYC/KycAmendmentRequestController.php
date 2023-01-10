<?php

namespace App\Http\Controllers\KYC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class KycAmendmentRequestController extends Controller
{
    //
    public function index(){
        if (!Gate::allows('all-kyc-amendment-requests-view')) {
            abort(403);
        }
        return view('kyc.amendments.index');
    }

    public function show($id){
        if (!Gate::allows('kyc-amendment-request-view')) {
            abort(403);
        }

        return view('kyc.amendments.show', compact('id'));
    }
}
