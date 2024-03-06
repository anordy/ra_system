<?php

namespace App\Http\Controllers\Business;

use App\Enum\CustomMessage;
use App\Enum\TaxTypePrefixStatus;
use App\Http\Controllers\Controller;
use App\Models\BusinessFile;
use App\Models\BusinessLocation;
use App\Models\BusinessTaxType;
use App\Models\BusinessType;
use App\Models\SystemSetting;
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
            return Storage::disk('local')->response($file->location);
        }

        // If they dont meet requirements, abort
        return abort(404);
    }

    public function getBusinessFileByLocation($location){
        if (!Gate::allows('business-registration-view')) {
            abort(403);
        }

        $location = decrypt($location);

        // Check who can access the file
        if ($location){
            return Storage::disk('local')->response($location);
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
            return Storage::disk('local')->response($taxpayer->tin_location);
        }

        return abort(404);
    }

    public function getCertificate($locationId, $taxTypeId){
        if (!Gate::allows('business-certificate-view')) {
            abort(403);
        }

        $taxTypeId = decrypt($taxTypeId);
        $locationId = decrypt($locationId);

        $location = BusinessLocation::with('business', 'business.taxpayer')->findOrFail($locationId);
        $tax = TaxType::findOrFail($taxTypeId);
        $taxType = BusinessTaxType::where('business_id', $location->business->id)->where('tax_type_id', $taxTypeId)->firstOrFail();
        
        $certificateNumber = $this->generateCertificateNumber($location, $tax->prefix);
        
        $url = env('TAXPAYER_URL') . route('qrcode-check.business.certificate', ['locationId' =>  base64_encode(strval($locationId)), 'taxTypeId' =>  base64_encode(strval($taxTypeId))], 0);
        
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => false])
            ->data($url)
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

        $signaturePath = SystemSetting::certificatePath();
        $commissinerFullName = SystemSetting::commissinerFullName();

        
        $pdf = PDF::loadView('business.certificate', compact('location', 'tax', 'dataUri', 'taxType', 'certificateNumber', 'signaturePath', 'commissinerFullName'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        return $pdf->stream();

    }

    public function generateCertificateNumber($location, $taxTypePrefix){
        $certificateNumber = $location->business->ztn_number;
        $taxRegionPrefix = $location->taxRegion->prefix;
        $ztn_location_number = $location->ztn_location_number;

        //If business is hotel and tax type is VAT change to Hotel VAT Prefix
        if ($location->business->business_type == BusinessType::HOTEL && $taxTypePrefix == TaxTypePrefixStatus::A) {
            $taxTypePrefix = TaxTypePrefixStatus::B;
        }

        return $certificateNumber.'-'.$taxRegionPrefix.$taxTypePrefix.$ztn_location_number;
    }
}
