<?php

namespace App\Http\Controllers\RoadInspectionOffence;

use App\Http\Controllers\Controller;
use App\Models\DlApplicationStatus;
use App\Models\DlDriversLicense;
use App\Models\DlDriversLicenseClass;
use App\Models\DlFee;
use App\Models\DlLicenseApplication;
use App\Models\RioRegister;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use WorkflowProcesssingTrait;

    public function index(){

        return view('road-inspection-offence.register-index');
    }

    public function create(){

        return view('road-inspection-offence.register-create');
    }

    public function removeRestriction($id){
        $id = decrypt($id);
        $register = RioRegister::query()->find($id);
        $register->update(['block_status'=>'REMOVED','block_removed_at'=>Carbon::now(),'block_removed_by'=>auth()->user()->id]);
        $register->save();
        return redirect()->route('rio.register.show',encrypt($id));
    }

    public function show($id){
        $id = decrypt($id);
        $register = RioRegister::query()->find($id);
        $mvr = $register->motor_vehicle_registration;
        $license = $register->drivers_license_owner->drivers_licenses()->latest()->first();

        return view('road-inspection-offence.register-show',compact('mvr','license','register'));
    }

}
