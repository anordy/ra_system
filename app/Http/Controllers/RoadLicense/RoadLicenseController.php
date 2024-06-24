<?php

namespace App\Http\Controllers\RoadLicense;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\RoadLicense\RoadLicense;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Log;
use PDF;

class RoadLicenseController extends Controller
{

    public function show($id) {
        try {
            $roadLicense = RoadLicense::with(['registration', 'taxpayer'])
                ->findOrFail(decrypt($id), ['id', 'mvr_registration_id', 'taxpayer_id', 'category_id', 'passengers_no', 'capacity', 'inspection_date', 'issued_date', 'expire_date', 'urn', 'marking', 'pass_mark', 'created_at', 'updated_at', 'status']);
            return view('road-license.show', compact('roadLicense'));
        } catch (\Exception $exception) {
            Log::error('ROAD-LICENSE-CONTROLLER-SHOW', [$exception]);
            session()->flash('error', CustomMessage::ERROR);
            return redirect()->back();
        }
    }

    public function sticker($id) {
        try {
            $roadLicense = RoadLicense::with(['registration', 'taxpayer'])
                ->findOrFail(decrypt($id), ['id', 'mvr_registration_id', 'taxpayer_id', 'category_id', 'passengers_no', 'capacity', 'inspection_date', 'issued_date', 'expire_date', 'urn', 'marking', 'pass_mark', 'created_at', 'updated_at', 'status']);

            $url = route('qrcode-check.road-license.sticker', ['roadLicenseId' => base64_encode(strval($roadLicense->id))]);

            $result = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => false])
                ->data($url)
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->size(220)
                ->margin(0)
                ->logoPath(public_path('/images/logo.png'))
                ->logoResizeToHeight(36)
                ->logoResizeToWidth(36)
                ->labelText('')
                ->labelAlignment(new LabelAlignmentCenter())
                ->build();

            header('Content-Type: ' . $result->getMimeType());

            $dataUri = $result->getDataUri();
            $stickerNo = "Z" . $roadLicense->created_at->year . sprintf("%'.05d\n", $roadLicense->id);

            $pdf = PDF::loadView('road-license.sticker', compact('roadLicense', 'dataUri', 'stickerNo'));
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);
            return $pdf->stream();
        } catch (\Exception $exception) {
            Log::error('ROAD-LICENSE-CONTROLLER-STICKER', [$exception]);
            session()->flash('error', CustomMessage::ERROR);
            return redirect()->back();
        }
    }

    public function index() {
        return view('road-license.index');
    }
}
