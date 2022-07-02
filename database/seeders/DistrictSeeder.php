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
        $unguja = Region::where('name','Unguja')->first();
        foreach($ungujaDistricts as $name){
            District::updateOrCreate([
                'name'=> $name,
                'region_id' => $unguja->id,
            ]);
        }

        $pembaDistricts = ['Micheweni','Wete'];
        $pemba = Region::where('name','Pemba')->first();
        foreach($pembaDistricts as $name){
            District::updateOrCreate([
                'name'=> $name,
                'region_id' => $pemba->id,
            ]);
        }
    }
}
