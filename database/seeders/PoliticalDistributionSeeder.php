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
        Region::updateOrCreate([
            'name' => 'Dar es Salaam'
        ]);

        District::updateOrCreate([
            'name' => 'Kinondoni',
            'region_id' => 1
        ]);

        Ward::updateOrCreate([
            'district_id' => 1,
            'name' => 'Kinondoni'
        ]);
    }
}
