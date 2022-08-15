<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\BusinessFile;
use App\Models\BusinessLocation;
use App\Models\Taxpayer;
use App\Models\TaxType;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
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

        $code = 'ZIN: ' . $location->zin . ", " .
            'Business Name: ' . $location->business->name . ", " .
            'Tax Type: ' . $tax->name . ", " .
            'Location: ' . "{$location->street}, {$location->district->name}, {$location->region->name}" . ", " .
            'Website: ' . 'https://uat.ubx.co.tz:8888/zrb_client/public/login';

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

        $pdf = PDF::loadView('business.certificate', compact('location', 'tax', 'dataUri'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();

    }
}
