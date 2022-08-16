<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Objection;
use App\Models\ObjectionAttachment;
use App\Models\WaiverAttachment;

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

    public function edit()
    {
        return view('assesments.objection.edit');
    }

    // public function approval($objectionId)
    // {
    //     $objection = Objection::findOrFail(decrypt($objectionId));
    //     $business = Business::find($objection->business_id);
    //     $files = ObjectionAttachment::where('objection_id', $objection->id)->get();
    //     return view('assesments.objection.approval', compact('objection','files', 'business'));
    // }
}
