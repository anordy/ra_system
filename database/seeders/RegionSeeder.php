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
        $regions = [
            [
                'name' => 'Kaskazini Unguja',
                'location' => 'unguja'
            ],
            [
                'name' => 'Kaskazini Pemba',
                'location' => 'pemba'
            ],
            [
                'name' => 'Kusini Unguja',
                'location' => 'unguja'
            ],
            [
                'name' => 'Kusini Pemba',
                'location' => 'pemba'
            ],
            [
                'name' => 'Mjini Magharibi',
                'location' => 'unguja'
            ],
        ];

        foreach($regions as $region){
            Region::updateOrCreate([
                'name' => $region['name'],
                'location' => $region['location']
            ]);
        }
    }
}
