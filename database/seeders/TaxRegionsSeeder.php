<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\TaxRegion;
use Illuminate\Database\Seeder;

class TaxRegionsSeeder extends Seeder
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
                'code' => 'headquarter',
                'name' => 'Headquarter',
                'prefix' => '01',
                'location' => Region::UNGUJA,
            ],
            [
                'code' => 'mjini',
                'name' => 'Mjini',
                'prefix' => '03',
                'location' => Region::UNGUJA,
            ],
            [
                'code' => 'kaskazini-unguja',
                'name' => 'Kaskazini Unguja',
                'prefix' => '04',
                'location' => Region::UNGUJA,
            ],
            [
                'code' => 'kusini-unguja',
                'name' => 'Kusini Unguja',
                'prefix' => '05',
                'location' => Region::UNGUJA,
            ],
            [
                'code' => 'kaskazini-pemba',
                'name' => 'Kaskazini Pemba',
                'prefix' => '06',
                'location' => Region::PEMBA,
            ],
            [
                'code' => 'kusini-pemba',
                'name' => 'Kusini Pemba',
                'prefix' => '07',
                'location' => Region::PEMBA,
            ],
        ];

        foreach ($regions as $region) {
            TaxRegion::create($region);
        }
    }
}
