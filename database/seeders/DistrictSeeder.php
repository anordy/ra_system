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
        $ungujaDistricts = ['Kaskazini A','Kaskazini B'];
        $ungujaRegions = Region::where('location', Region::UNGUJA)->get();
        foreach ($ungujaRegions as $region){
            foreach($ungujaDistricts as $name){
                District::updateOrCreate([
                    'name'=> $name,
                    'region_id' => $region->id,
                ]);
            }
        }

        $pembaDistricts = ['Micheweni', 'Wete'];
        $pembaRegions = Region::where('location', Region::PEMBA)->get();
        foreach ($pembaRegions as $pembaRegion) {
            foreach($pembaDistricts as $name){
                District::updateOrCreate([
                    'name'=> $name,
                    'region_id' => $pembaRegion->id,
                ]);
            }
        }




        //To be confirmed when wards for new Districts provided
        // $regions = Region::all();

        // foreach ($regions as $region) {
        //     $districts = [];
        //     if ($region->name == 'Kaskazini Unguja' ) {
        //         $districts = ['Kaskazini A','Kaskazini B'];
        //     } else if($region->name == 'Kaskazini Pemba') {
        //         $districts = ['Micheweni', 'Wete'];
        //     } else if($region->name == 'Kusini Unguja') {
        //         $districts = ['Kati','Kusini'];
        //     } else if($region->name == 'Kusini Pemba') {
        //         $districts = ['Chakechake', 'Mkoani'];
        //     } else if($region->name == 'Mjini Magharibi') {
        //         $districts = ['Mjini', 'Magharibi A', 'Magharibi B'];
        //     }

        //     if(!empty($districts)) {
        //         foreach($districts as $name){
        //             District::updateOrCreate([
        //                 'name'=> $name,
        //                 'region_id' => $region->id,
        //             ]);
        //         }
        //     }
        // }
    }
}
