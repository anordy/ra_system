<?php

namespace App\Http\Controllers\MVR;

use App\Enum\MvrTemporaryTransportStatus;
use App\Http\Controllers\Controller;
use App\Models\MvrTemporaryTransport;
use App\Models\SystemSetting;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use PDF;

class TemporaryTransportsController extends Controller
{
    public function index(){
        return view('mvr.temporary-transports.index');
    }

    public function show($transport){
        $transport = MvrTemporaryTransport::findOrFail(decrypt($transport));
        return view('mvr.temporary-transports.show', compact('transport'));
    }

    public function getTransportLetter($transport){
        $transport = MvrTemporaryTransport::findOrFail(decrypt($transport));

        if (!($transport->status == MvrTemporaryTransportStatus::APPROVED)){
            session()->flash('error', __('Temporary Transport not approved'));
            return back();
        }

        $url = env('TAXPAYER_URL') . route('qrcode-check.mvr.temporary-transport', base64_encode(strval($transport->id)), 0);

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


        $pdf = PDF::loadView('mvr.temporary-transports.letter', compact('transport','dataUri', 'signaturePath', 'commissinerFullName'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        return $pdf->stream();
    }
}
