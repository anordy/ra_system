<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationStatus;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

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
        $motor_vehicle = MvrMotorVehicle::query()->find($id);
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


    public function simulatePayment($id){
        $id = decrypt($id);
        $status = MvrRegistrationStatus::query()
            ->firstOrCreate(['name'=>MvrRegistrationStatus::STATUS_PLATE_NUMBER_PRINTING]);
        MvrMotorVehicle::query()
            ->where(['id'=>$id])
            ->update([
                'mvr_registration_status_id'=>$status->id
            ]);

        $reg =  MvrMotorVehicle::query()
            ->where(['id'=>$id])->first()->current_registration;
        $reg_type = $reg->registration_type;
        try {
            DB::beginTransaction();
            $plate_number = MvrMotorVehicleRegistration::getNexPlateNumber($reg_type);
            $plate_status = MvrPlateNumberStatus::query()->firstOrCreate(['name' => MvrPlateNumberStatus::STATUS_GENERATED]);
            $reg->update(['plate_number'=>$plate_number,'mvr_plate_number_status_id'=>$plate_status->id]);
            DB::commit();
        }catch (\Exception $e){
            session()->flash('error', 'Could not update status');
            DB::rollBack();
        }
        return redirect()->route('mvr.show',encrypt($id));
    }


    public function plateNumbers(){
        return view('mvr.plate-numbers');
    }

}
