<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\WaiverObjection;
use Illuminate\Http\Request;

class WaiverObjectionController extends Controller
{
     public function index()
    {
        return view('assesments.waiverobjection.index');
    }

    public function create($location_id, $tax_type_id)
    {
        $location_id = $location_id;
        $tax_type_id = $tax_type_id;
        return view('assesments.waiverobjection.create', compact('location_id', 'tax_type_id'));
    }

    public function show($waiverObjectionId)
    { 
        $waiverObjection = WaiverObjection::findOrfail(decrypt($waiverObjectionId));
        $business = Business::find($waiverObjectionId->business_id);
        return view('assesments.waiverobjection.show',compact('waiverObjection','business'));
    }

        public function approval($waiverObjectionId)
    {
        $waiverObjection = WaiverObjection::findOrFail(decrypt($waiverObjectionId));
        $business = Business::find($waiverObjection->business_id);

        return view('assesments.waiverobjection.approval', compact('waiverObjection','business'));
    }
}
