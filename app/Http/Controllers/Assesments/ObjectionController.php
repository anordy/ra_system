<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Objection;

class ObjectionController extends Controller
{
    public function index()
    {
        return view('assesments.objection.index');
    }

    public function create($location_id, $tax_type_id)
    {
        $location_id = $location_id;
        $tax_type_id = $tax_type_id;
        return view('assesments.objection.create', compact(['location_id', 'tax_type_id']));
    }

    public function show($business_id)
    {
        $objection = Objection::findOrfail(decrypt($business_id));
        $business = Business::find($objection->business_id);
        return view('assesments.objection.show',compact('objection', 'business'));
    }

    public function edit()
    {
        return view('assesments.objection.edit');
    }
}
