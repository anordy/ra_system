<?php

namespace App\Http\Controllers\MVR;

use App\Http\Controllers\Controller;
use App\Services\TRA\ServiceRequest;

class TRAChassisSearchController extends Controller
{

	public function search($chassis){
        $result = ServiceRequest::searchMotorVehicleByChassis($chassis);
        $motor_vehicle = [];
        $message = '';
        $result_status = $result['status'];
        if ($result['status']=='success'){
            $motor_vehicle = $result['data'];
        }else{
            $message = $result['message'];
        }
		return view('mvr.chassis-search',compact('motor_vehicle','chassis','message','result_status'));
	}
}
