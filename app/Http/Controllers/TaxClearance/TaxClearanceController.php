<?php

namespace App\Http\Controllers\TaxClearance;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class TaxClearanceController extends Controller
{
    //
    public function requestList(){
        return view('tax-clearance.requests');

    }

    public function viewRequest($id){
        $business_location_id = decrypt($id);
        return view('tax-clearance.clearance-request', compact('business_location_id'));

    }
}
