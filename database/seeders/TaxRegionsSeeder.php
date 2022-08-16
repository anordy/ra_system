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
                'code' => 'mjini',
                'name' => 'Mjini',
                'prefix' => '02',
            ],
            [
                'code' => 'kaskazini-unguja',
                'name' => 'Kaskazini Unguja',
                'prefix' => '03',
            ],
            [
                'code' => 'kusini-unguja',
                'name' => 'Kusini Unguja',
                'prefix' => '04',
            ],
            [
                'code' => 'kaskazini-pemba',
                'name' => 'Kaskazini Pemba',
                'prefix' => '05',
            ],
            [
                'code' => 'kusini-pemba',
                'name' => 'Kusini Pemba',
                'prefix' => '06',
            ],
        ];

        foreach ($regions as $region) {
            TaxRegion::create($region);
        }
    }
}
