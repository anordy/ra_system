<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Waiver;

class WaiverController extends Controller
{
    public function index()
    {
        return view('assesments.waiver.index');
    }

  

    public function create($location_id, $tax_type_id)
    {
        $location_id = $location_id;
        $tax_type_id = $tax_type_id;
        return view('assesments.waiver.create', compact('location_id', 'tax_type_id'));
    }
    public function show($waiverId)
    {
        
        $waiver = Waiver::findOrfail(decrypt($waiverId));
        $business = Business::find($waiver->business_id);
        return view('assesments.waiver.show',compact('waiver','business'));
    }

       public function edit()
    {
        return view('assesments.waiver.edit');
    }
}
