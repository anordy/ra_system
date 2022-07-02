<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Region;
use App\Models\Ward;
use Illuminate\Database\Seeder;

class PoliticalDistributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $unguja = Region::create([
            'name' => 'Unguja'
        ]);

        $ka = District::create([
            'name' => 'Kaskazini A',
            'region_id' => $unguja->id
        ]);

        Ward::create([
            'district_id' => $ka->id,
            'name' => 'Bandamaji'
        ]);

        Ward::create([
            'district_id' => $ka->id,
            'name' => 'Nungwi'
        ]);
    }
}
