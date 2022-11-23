<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BusinessFile;
use App\Models\BusinessLocation;
use App\Models\BusinessTaxType;
use App\Models\Taxpayer;
use App\Models\TaxType;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use PDF;

class BusinessFileController extends Controller
{
    public function index()
    {
        if (!Gate::allows('setting-business-file-view')) {
            abort(403);
        }
        return view('settings.business-files');
    }

    public function getBusinessFile($fileId){
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }

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
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }

        $taxpayer = Taxpayer::findOrFail(decrypt($taxpayerId));

        if ($taxpayer->tin_location){
            return Storage::response($taxpayer->tin_location);
        }

        return abort(404);
    }

    public function getCertificate($locationId, $taxTypeId){
        if (!Gate::allows('business-certificate-view')) {
            abort(403);
        }
        $locationId = decrypt($locationId);
        $taxTypeId = decrypt($taxTypeId);
        $location = BusinessLocation::with('business', 'business.taxpayer')->find($locationId);
        $tax = TaxType::find($taxTypeId);
        $taxType = BusinessTaxType::where('business_id', $location->business->id)->where('tax_type_id', $taxTypeId)->firstOrFail();

        $certificateNumber = $this->generateCertificateNumber($location, $tax->prefix);

        $code = 'ZIN: ' . $location->zin . ", " .
            'Business Name: ' . $location->business->name . ", " .
            'Tax Type: ' . $tax->name . ", " .
            'Location: ' . "{$location->street}, {$location->district->name}, {$location->region->name}";
        
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => false])
            ->data($code)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(207)
            ->margin(0)
            ->logoPath(public_path('/images/logo.png'))
            ->logoResizeToHeight(36)
            ->logoResizeToWidth(36)
            ->labelText('')
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        header('Content-Type: ' . $result->getMimeType());

        $dataUri = $result->getDataUri();
        
        $pdf = PDF::loadView('business.certificate', compact('location', 'tax', 'dataUri', 'taxType', 'certificateNumber'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();

    }

    public function generateCertificateNumber($location, $taxTypePrefix){
        $certificateNumber = $location->business->ztn_number;
        $taxRegionPrefix = $location->taxRegion->prefix;
        $ztn_location_number = $location->ztn_location_number;

        //If business is hotel and tax type is VAT change to Hotel VAT Prefix
        if ($location->business->business_type == 'hotel' && $taxTypePrefix == 'A') {
            $taxTypePrefix = 'B';
        }
        
        $certificateNumber = $certificateNumber.'-'.$taxRegionPrefix.$taxTypePrefix.$ztn_location_number;
        return $certificateNumber;
    }
}
