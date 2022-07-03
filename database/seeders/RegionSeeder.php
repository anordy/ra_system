<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regions = ['Unguja','Pemba'];
        foreach($regions as $region){
            Region::updateOrCreate([
                'name'=>$region
            ]);
        }
    }
}
