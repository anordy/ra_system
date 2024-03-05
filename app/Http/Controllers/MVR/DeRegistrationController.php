<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Models\MvrDeregistration;
use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrMotorVehicle;
use App\Models\MvrRequestStatus;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DeRegistrationController extends Controller
{

    public function index()
    {
        if (!Gate::allows('motor-vehicle-deregistration')) {
            abort(403);
        }
        return view('mvr.de-registration.index');
    }

    public function show($id)
    {
        if (!Gate::allows('motor-vehicle-deregistration')) {
            abort(403);
        }
        $mvrDeregistration = MvrDeregistration::query()->findOrFail(decrypt($id));
        return view('mvr.de-registration.show', compact('mvrDeregistration'));
    }

    public function file($path)
    {
        if ($path) {
            try {
                return Storage::disk('local')->response(decrypt($path));
            } catch (Exception $e) {
                Log::error($e);
                abort(404);
            }
        }

        return abort(404);
    }

    public function deRegistrationCertificate($id){
        $id = decrypt($id);

        $deregistration = MvrDeregistration::query()->findOrFail($id);

        header('Content-Type: application/pdf' );

        $url = env('TAXPAYER_URL') . route('qrcode-check.mvr.de-registration', ['id' =>  base64_encode(strval($id))], 0);

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

        $dataUri = $result->getDataUri();

        $pdf = PDF::loadView('mvr.pdfs.certificate-of-de-registration', compact('deregistration', 'dataUri'));
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();
    }


}
