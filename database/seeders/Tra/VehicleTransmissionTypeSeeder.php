<?php

namespace Database\Seeders\Tra;

use App\Models\Tra\TraVehicleTransmissionType;
use Illuminate\Database\Seeder;

class VehicleTransmissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transmissions = array(
            array("code"=>"001", "name"=>"Manual"),
            array("code"=>"002", "name"=>"Automatic"),
            array("code"=>"003", "name"=>"Semi-automatic"),
            array("code"=>"004", "name"=>"NOT APPLICABLE")
        );

        foreach ($transmissions as $transmission) {
//            TraVehicleTransmissionType::updateOrCreate([
//                'code' => $transmission['code']
//            ],[
//                'code' => $transmission['code'],
//                'name' => $transmission['name']
//            ]);
        }

    }
}
