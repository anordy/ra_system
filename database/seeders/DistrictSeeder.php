<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Region;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = Region::all();

        foreach ($regions as $region) {
            $districts = [];
            if ($region->name == 'Kaskazini Unguja' ) {
                $districts = ['Kaskazini A','Kaskazini B'];
            } else if($region->name == 'Kaskazini Pemba') {
                $districts = ['Micheweni', 'Wete'];
            } else if($region->name == 'Kusini Unguja') {
                $districts = ['Kati','Kusini'];
            } else if($region->name == 'Kusini Pemba') {
                $districts = ['Chakechake', 'Mkoani'];
            } else if($region->name == 'Mjini') {
                $districts = ['Mjini', 'Magharibi A', 'Magharibi B'];
            }

            if(!empty($districts)) {
                foreach($districts as $name){
                    District::updateOrCreate([
                        'name'=> $name,
                        'region_id' => $region->id,
                        'is_approved' => 1,
                    ]);
                }
            }
        }
    }
}
