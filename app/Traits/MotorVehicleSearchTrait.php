<?php

namespace App\Traits;

use App\Enum\GeneralConstant;
use App\Models\MvrMotorVehicle;
use App\Models\MvrMotorVehicleRegistration;
use App\Models\MvrRegistration;
use App\Models\MvrRegistrationStatus;
use App\Models\Tra\ChassisNumber;
use Illuminate\Support\Facades\DB;


trait MotorVehicleSearchTrait
{

    public function searchRegistered($type, $number)
    {
        $status = MvrRegistrationStatus::query()->firstOrCreate([
            'name' => MvrRegistrationStatus::STATUS_REGISTERED
        ]);

        if ($type == GeneralConstant::CHASSIS) {
            $mv = MvrMotorVehicle::query()
                ->where(['chassis_number' => $number])
                ->where(['mvr_registration_status_id' => $status->id])
                ->first();

            if (!$mv) {
                return null;
            }

            return $mv;
        } else {
            $motor_vehicle = MvrRegistration::query()
                ->where(['plate_number' => $number])
                ->first();

            if ($motor_vehicle == null) {
                return null;
            }

            return $motor_vehicle;
        }
    }

    public function searchDeRegistered($type, $number)
    {
        $status = MvrRegistrationStatus::query()->firstOrCreate(['name' => MvrRegistrationStatus::STATUS_REGISTERED]);
        if ($type == 'chassis') {
            $isReadyForDeregistration = ChassisNumber::where('chassis_number', $number)->where('status', 2)->first();
            if ($isReadyForDeregistration) {
                return MvrMotorVehicle::query()
                    ->where(['chassis_number' => $number])
                    ->where(['mvr_registration_status_id' => $status->id])
                    ->first();
            }
            return null;
        } else {
            $motor_vehicle = MvrMotorVehicleRegistration::query()
                ->where(['plate_number' => $number])
                ->first()->motor_vehicle ?? null;
            if ($motor_vehicle == null) {
                return null;
            }

            return ($motor_vehicle->mvr_registration_status_id ?? null) == $status->id ? $motor_vehicle : null;
        }
    }

}
