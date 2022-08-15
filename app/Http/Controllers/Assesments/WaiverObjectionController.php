<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\DisputeAttachment;
use App\Models\Verification\TaxVerificationAssessment;
use App\Models\WaiverObjection;

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

    public function approval($waiverObjectionId)
    {
        $waiverObjection = WaiverObjection::findOrFail(decrypt($waiverObjectionId));
        $business = Business::find($waiverObjection->business_id);
        $files = DisputeAttachment::where('dispute_id', $waiverObjection->id)->get();
        return view('assesments.waiverobjection.approval', compact('waiverObjection', 'business', 'files'));
    }
}
