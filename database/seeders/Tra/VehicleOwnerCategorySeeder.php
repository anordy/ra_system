<?php

namespace Database\Seeders\Tra;

use App\Models\Tra\TraVehicleOwnerCategory;
use Illuminate\Database\Seeder;

class VehicleOwnerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = array(
            array("val0"=>"A", "val1"=>"Assocations or Clubs"),
            array("val0"=>"C", "val1"=>"Company"),
            array("val0"=>"COS", "val1"=>"Cooperative Society"),
            array("val0"=>"D", "val1"=>"Diplomats"),
            array("val0"=>"E", "val1"=>"Expatriate"),
            array("val0"=>"F", "val1"=>"Foreign Missions"),
            array("val0"=>"G", "val1"=>"Government organisation"),
            array("val0"=>"HD", "val1"=>"Honorary Diplomat"),
            array("val0"=>"I", "val1"=>"Organisation under UNDP"),
            array("val0"=>"L", "val1"=>"Local Government"),
            array("val0"=>"NGO", "val1"=>"Non Government Organisation"),
            array("val0"=>"O", "val1"=>"Other Govt. Organisations"),
            array("val0"=>"P", "val1"=>"Parastatal"),
            array("val0"=>"PSH", "val1"=>"Partnership"),
            array("val0"=>"R", "val1"=>"Religious Organisations"),
            array("val0"=>"S", "val1"=>"Sole Proprietor"),
            array("val0"=>"T", "val1"=>"Tanzania Citizen")
        );

        foreach ($array as $item) {
            TraVehicleOwnerCategory::updateOrCreate([
                'code' => $item['val0']
            ],[
                'code' => $item['val0'],
                'name' => $item['val1'],
            ]);
        }
    }
}
