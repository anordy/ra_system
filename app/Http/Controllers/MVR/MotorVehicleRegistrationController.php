<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Mvr\DeRegistrationRequest;
use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistration;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRegistrationType;
use App\Models\MvrRequestStatus;
use App\Models\SystemSetting;
use App\Services\Api\TraInternalService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class MotorVehicleRegistrationController extends Controller
{

	public function index(){
        if (!Gate::allows('motor-vehicle-registration')) {
            abort(403);
        }
        return view('mvr.registration.index');
    }

    public function show($id){
        if (!Gate::allows('motor-vehicle-registration')) {
            abort(403);
        }
        $motorVehicle = MvrRegistration::findOrFail(decrypt($id));
        return view('mvr.registration.show', compact('motorVehicle'));
    }



    public function plateNumbers(){
        if (!Gate::allows('motor-vehicle-plate-number-printing')) {
            abort(403);
        }
        return view('mvr.plate-numbers');
    }


    public function printCertificateOfWorth($id){
        $id = decrypt($id);
        $motor_vehicle = MvrMotorVehicle::query()->findOrFail($id);

        header('Content-Type: application/pdf' );

        $signaturePath = SystemSetting::certificatePath();
        $commissinerFullName = SystemSetting::commissinerFullName();

        $pdf = PDF::loadView('mvr.pdfs.certificate-of-worth', compact('motor_vehicle', 'signaturePath', 'commissinerFullName' ));
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);
        return $pdf->stream();
    }

    public function registrationCertificate($id){
        $id = decrypt($id);
        $motor_vehicle = MvrRegistration::query()->findOrFail($id);

        header('Content-Type: application/pdf' );

        $pdf = PDF::loadView('mvr.pdfs.certificate-of-registration', compact('motor_vehicle' ));
        $pdf->setPaper('legal', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->stream();
    }

    public function deRegistrationCertificate($id){
        $id = decrypt($id);
        $motor_vehicle = MvrMotorVehicle::query()->findOrFail($id);
        $request = MvrDeRegistrationRequest::query()
            ->where(['mvr_request_status_id'=>MvrRequestStatus::query()->where(['name'=>MvrRequestStatus::STATUS_RC_ACCEPTED])->first()->id,'mvr_motor_vehicle_id'=>$id])
        ->firstOrFail();

        header('Content-Type: application/pdf' );

        $pdf = PDF::loadView('mvr.pdfs.certificate-of-de-registration', compact('motor_vehicle','request' ));
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->stream();
    }


    public function showFile($path)
    {
        try {
            return Storage::disk('local')->response(decrypt($path));
        } catch (\Exception $e) {
            report($e);
        }
        return abort(404);
    }
}
