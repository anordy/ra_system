<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Mvr\DeRegistrationRequest;
use App\Models\MvrDeRegistrationRequest;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrPlateNumberStatus;
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


    public function registeredIndex(){
        return view('mvr.registered-index');
    }


	public function index(){

		return view('mvr.index');
	}

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id){
        $id = decrypt($id);
        $motor_vehicle = MvrMotorVehicle::query()->findOrFail($id);
        return view('mvr.show',compact('motor_vehicle'));
    }


    /**
     * @param $id
     * @return RedirectResponse
     */
    public function submitInspection($id){
        $id = decrypt($id);
        $status = MvrRegistrationStatus::query()
            ->firstOrCreate(['name'=>MvrRegistrationStatus::STATUS_REVENUE_OFFICER_APPROVAL]);
        MvrMotorVehicle::query()
            ->where(['id'=>$id])
            ->update([
                'mvr_registration_status_id'=>$status->id
        ]);
        return redirect()->route('mvr.show',encrypt($id));
    }


    // TODO: Shift to API upon payment completed
    public function simulatePayment($id){
        $id = decrypt($id);
        $reg =  MvrMotorVehicle::query()
            ->where(['id'=>$id])->first()->current_registration;
        $reg_type = $reg->registration_type;
        try {
            DB::beginTransaction();
            $status = MvrRegistrationStatus::query()
                ->firstOrCreate(['name'=>MvrRegistrationStatus::STATUS_PLATE_NUMBER_PRINTING]);
            $mv = MvrMotorVehicle::query()
                ->findOrFail($id);
            $mv->update([
                    'mvr_registration_status_id'=>$status->id
                ]);

            if ($reg_type->name == MvrRegistrationType::TYPE_PRIVATE_GOLDEN){
                $plate_number = $reg->plate_number;
            }else if ($reg_type->external_defined != 1){
                $plate_number = MvrMotorVehicleRegistration::getNexPlateNumber($reg_type,$mv->class);
            }

            if (!empty($reg->current_personalized_registration)){
                $reg->current_personalized_registration->update(['status'=>'ACTIVE']);
            }

            $plate_status = MvrPlateNumberStatus::query()->firstOrCreate(['name' => MvrPlateNumberStatus::STATUS_GENERATED]);
            $reg->update(['plate_number'=>$plate_number,'mvr_plate_number_status_id'=>$plate_status->id,'registration_date'=>date('Y-m-d')]);
            DB::commit();

            // TODO: Send Registration status as a job
            $traService = new TraInternalService();
            $traService->postPlateNumber($mv->chassis_number, $plate_number, 'registration');
        }catch (\Exception $e){
            session()->flash('error', 'Could not update status');
            DB::rollBack();
            Log::error($e);
        }
        return redirect()->route('mvr.show',encrypt($id));
    }


    public function plateNumbers(){
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
        $motor_vehicle = MvrMotorVehicle::query()->findOrFail($id);

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
