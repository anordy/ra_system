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
        Region::create([
            'name' => 'Dar es Salaam'
        ]);

        District::create([
            'name' => 'Kinondoni',
            'region_id' => 1
        ]);

        Ward::create([
            'district_id' => 1,
            'name' => 'Kinondoni'
        ]);
    }
}
