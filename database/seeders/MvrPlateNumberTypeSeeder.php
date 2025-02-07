<?php

namespace Database\Seeders;

use App\Models\MvrPlateNumberType;
use App\Models\MvrPlateSize;
use Illuminate\Database\Seeder;

class MvrPlateNumberTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'code' => MvrPlateNumberType::NON_SPECIAL,
                'name' => "Non Special(Ordinary)"
            ],
            [
                'code' => MvrPlateNumberType::SPECIAL_NAME,
                'name' => "Special Name"
            ],
            [
                'code' => MvrPlateNumberType::PERSONALIZED,
                'name' => "Personalized"
            ]
        ];

        foreach ($types as $type) {
            MvrPlateNumberType::updateOrCreate($type);
        }

        $sizes = [
            [
                'name' => '400x200'
            ]
        ];

        foreach ($sizes as $size) {
            MvrPlateSize::updateOrCreate($size);
        }
    }
}
