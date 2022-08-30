<?php

namespace App\Traits;

use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrRegistrationStatus;
use Illuminate\Support\Facades\DB;


trait MotorVehicleSearchTrait
{

    public function searchRegistered($type, $number)
    {
        $status = MvrRegistrationStatus::query()->firstOrCreate(['name'=>MvrRegistrationStatus::STATUS_REGISTERED]);
        if ($type=='chassis'){
            return MvrMotorVehicle::query()
                ->where(['chassis_number'=>$number])
                ->where(['mvr_registration_status_id'=>$status->id])
                ->first();
        }else{
            $motor_vehicle = MvrMotorVehicleRegistration::query()
                    ->where(['plate_number'=>$number])
                    ->first()->motor_vehicle ?? null;

            return ($motor_vehicle->mvr_registration_status_id ?? null) == $status->id ? $motor_vehicle: null;
        }
    }
}
