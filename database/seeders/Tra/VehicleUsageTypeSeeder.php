<?php

namespace Database\Seeders\Tra;

use App\Models\Tra\TraVehicleUsageType;
use Illuminate\Database\Seeder;

class VehicleUsageTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = array(
            array("val0"=>"000", "val1"=>"Not applicable"),
            array("val0"=>"001", "val1"=>"Private or Normal"),
            array("val0"=>"002", "val1"=>"Commercial"),
            array("val0"=>"003", "val1"=>"Taxi or Cab"),
            array("val0"=>"004", "val1"=>"Emergency"),
            array("val0"=>"005", "val1"=>"Government"),
            array("val0"=>"006", "val1"=>"Diplomatic use"),
            array("val0"=>"007", "val1"=>"Government Donor Funded Projects"),
            array("val0"=>"008", "val1"=>"Post Entry")
        );

        foreach ($array as $item) {
            TraVehicleUsageType::updateOrCreate([
                'code' => $item['val0']
            ],[
                'code' => $item['val0'],
                'name' => $item['val1'],
            ]);
        }

    }
}
