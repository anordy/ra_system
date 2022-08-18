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
}
