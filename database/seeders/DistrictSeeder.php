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
    }
}
