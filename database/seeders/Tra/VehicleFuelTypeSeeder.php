<?php

namespace Database\Seeders\Tra;

use App\Models\Tra\TraVehicleFuelType;
use Illuminate\Database\Seeder;

class VehicleFuelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = array(
            array("val0"=>"001", "val1"=>"Not applicable"),
            array("val0"=>"002", "val1"=>"Petrol"),
            array("val0"=>"003", "val1"=>"Diesel"),
            array("val0"=>"004", "val1"=>"Electricity"),
            array("val0"=>"005", "val1"=>"Gas")
        );

        foreach ($array as $item) {
            TraVehicleFuelType::updateOrCreate([
                'code' => $item['val0']
            ],[
                'code' => $item['val0'],
                'name' => $item['val1'],
            ]);
        }

    }
}
