<?php

namespace Database\Seeders;

use App\Models\MvrColor;
use App\Models\MvrMake;
use App\Models\MvrModel;
use Illuminate\Database\Seeder;

class MvrPlateNumberColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = [
            [
                'mvr_registration_type_id' => 1,
                'color' => 'White/Black'
            ],
            [
                'mvr_registration_type_id' => 2,
                'color' => 'White/Black'
            ]
        ];

        foreach ($colors as $color) {
            MvrColor::query()->updateOrcreate($color);
        }
    }
}
