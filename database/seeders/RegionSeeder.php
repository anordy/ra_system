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
        $regions = ['Unguja Kaskazini','Pemba Kaskazini', 'Unguja Kusini', 'Pemba Kusini'];

        foreach($regions as $region){
            Region::updateOrCreate([
                'name' => $region,
                'location' => strtolower(explode(' ', $region)[0])
            ]);
        }
    }
}
