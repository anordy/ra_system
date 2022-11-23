<?php

namespace Database\Seeders;

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
            ],
            [
                'code' => 'lto',
                'name' => 'LTO',
                'prefix' => '02',
            ],
            [
                'code' => 'mjini',
                'name' => 'Mjini',
                'prefix' => '03',
            ],
            [
                'code' => 'kaskazini-unguja',
                'name' => 'Kaskazini Unguja',
                'prefix' => '04',
            ],
            [
                'code' => 'kusini-unguja',
                'name' => 'Kusini Unguja',
                'prefix' => '05',
            ],
            [
                'code' => 'kaskazini-pemba',
                'name' => 'Kaskazini Pemba',
                'prefix' => '06',
            ],
            [
                'code' => 'kusini-pemba',
                'name' => 'Kusini Pemba',
                'prefix' => '07',
            ],
        ];

        foreach ($regions as $region) {
            TaxRegion::create($region);
        }
    }
}
