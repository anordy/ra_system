<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrMotorVehicle;
use App\Models\MvrRegistration;
use App\Models\MvrRequestStatus;
use App\Models\SystemSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MotorVehicleRegistrationController extends Controller
{

    public function index()
    {
        if (!Gate::allows('motor-vehicle-registration')) {
            abort(403);
        }
        return view('mvr.registration.index');
    }

    public function show($id)
    {
        if (!Gate::allows('motor-vehicle-registration')) {
            abort(403);
        }
        $motorVehicle = MvrRegistration::findOrFail(decrypt($id));
        return view('mvr.registration.show', compact('motorVehicle'));
    }


    public function plateNumbers()
    {
        if (!Gate::allows('motor-vehicle-plate-number-printing')) {
            abort(403);
        }
        return view('mvr.plate-numbers');
    }


    public function printCertificateOfWorth($id)
    {
        $id = decrypt($id);
        $motor_vehicle = MvrMotorVehicle::query()->findOrFail($id);

        header('Content-Type: application/pdf');

        $signaturePath = SystemSetting::certificatePath();
        $commissinerFullName = SystemSetting::commissinerFullName();

        $pdf = PDF::loadView('mvr.pdfs.certificate-of-worth', compact('motor_vehicle', 'signaturePath', 'commissinerFullName'));
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);
        return $pdf->stream();
    }

    public function registrationCertificate($id)
    {
        $id = decrypt($id);
        $motor_vehicle = MvrRegistration::query()->findOrFail($id);

        header('Content-Type: application/pdf');

        $url = env('TAXPAYER_URL') . route('qrcode-check.mvr.registration', ['id' =>  base64_encode(strval($id))], 0);

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

        $pdf = PDF::loadView('mvr.pdfs.certificate-of-registration', compact('motor_vehicle', 'dataUri'));
        $pdf->setPaper('legal', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->stream();
    }

    public function deRegistrationCertificate($id)
    {
        $id = decrypt($id);

        $motor_vehicle = MvrMotorVehicle::query()->findOrFail($id);
        $request = MvrDeRegistrationRequest::query()
            ->where([
                'mvr_request_status_id' => MvrRequestStatus::query()
                    ->select('id')
                    ->where(['name' => MvrRequestStatus::STATUS_RC_ACCEPTED])
                    ->first()->id,
                'mvr_motor_vehicle_id' => $id])
            ->firstOrFail();

        header('Content-Type: application/pdf');

        $pdf = PDF::loadView('mvr.pdfs.certificate-of-de-registration', compact('motor_vehicle', 'request'));
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->stream();
    }


    public function showFile($path)
    {
        try {
            return Storage::disk('local')->response(decrypt($path));
        } catch (\Exception $e) {
            Log::error('MVR-SHOW-FILE', [$e]);
            report($e);
        }
        return abort(404);
    }
}
