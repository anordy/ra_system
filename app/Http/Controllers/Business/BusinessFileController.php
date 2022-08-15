<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessFile;
use App\Models\BusinessLocation;
use App\Models\Taxpayer;
use App\Models\TaxType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

class BusinessFileController extends Controller
{
    public function index()
    {
        return view('settings.business-files');
    }

    public function getBusinessFile($fileId){
        $file = BusinessFile::findOrFail(decrypt($fileId));

        // Check who can access the file
        if ($file){
            return Storage::response($file->location);
        }

        // If they dont meet requirements, abort
        return abort(404);
    }

    public function getTinFile($taxpayerId)
    {
        $taxpayer = Taxpayer::findOrFail(decrypt($taxpayerId));

        if ($taxpayer->tin_location){
            return Storage::response($taxpayer->tin_location);
        }

        return abort(404);
    }

    public function getCertificate($locationId, $taxTypeId){
        $locationId = decrypt($locationId);
        $taxTypeId = decrypt($taxTypeId);
        $location = BusinessLocation::with('business', 'business.taxpayer')->find($locationId);
        $tax = TaxType::find($taxTypeId);

        $pdf = PDF::loadView('business.certificate', compact('location', 'tax'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();

    }
}
