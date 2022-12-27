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
                'location' => 'unguja',
                'is_approved' => 1
            ],
            [
                'name' => 'Kaskazini Pemba',
                'location' => 'pemba',
                'is_approved' => 1
,
                ],
            [
                'name' => 'Kusini Unguja',
                'location' => 'unguja',
                'is_approved' => 1

            ],
            [
                'name' => 'Kusini Pemba',
                'location' => 'pemba',
                'is_approved' => 1
,
                ],
            [
                'name' => 'Mjini Magharibi',
                'location' => 'unguja',
                'is_approved' => 1

            ],
        ];

        foreach($regions as $region){
            Region::updateOrCreate([
                'name' => $region['name'],
                'location' => $region['location'],
                'is_approved' => $region['is_approved']
            ]);
        }
    }
}
