<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Models\MvrFee;
use App\Models\MvrFeeType;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrPersonalizedPlateNumberRegistration;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationChangeRequest;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRegistrationType;
use App\Models\MvrRequestStatus;
use App\Services\TRA\ServiceRequest;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use App\Traits\MotorVehicleSearchTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class WrittenOffVehiclesController extends Controller
{

    use MotorVehicleSearchTrait;

	public function index(){
		return view('mvr.written-off-index');
	}

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id){
        $id = decrypt($id);
        /** @var MvrRegistrationChangeRequest $change_req */
        $change_req = MvrRegistrationChangeRequest::query()->find($id);
        $motor_vehicle =$change_req->current_registration->motor_vehicle;
        return view('mvr.reg-change-req-show',compact('motor_vehicle','change_req'));
    }

    public function search($type,$number){
        $motor_vehicle = $this->searchRegistered($type,$number);
        $search_type = ucwords(preg_replace('/-/',' ',$type));
        $action = 'mvr.written-off-motor-vehicle';
        $result_route = 'mvr.internal-search-wo';
        return view('mvr.internal-search',compact('motor_vehicle','search_type','number','action','result_route'));
    }

}
