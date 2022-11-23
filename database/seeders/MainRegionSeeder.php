<?php

namespace Database\Seeders;

use App\Models\MainRegion;
use Illuminate\Database\Seeder;

class MainRegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $regions = [
            [
                'code' => '05',
                'name' => 'Unguja',
                'prefix' => 'UNG',
            ],
            [
                'code' => '06',
                'name' => 'Pemba',
                'prefix' => 'PMB',
            ],
        ];

        foreach($regions as $region){
            MainRegion::updateOrCreate([
                'code' => $region['code'],
                'name' => $region['name'],
                'prefix' => $region['prefix']
            ]);
        }
    }
}
