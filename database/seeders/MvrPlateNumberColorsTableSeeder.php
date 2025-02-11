<?php

namespace Database\Seeders;

use App\Models\MvrColor;
use App\Models\MvrMake;
use App\Models\MvrModel;
use App\Models\MvrRegistrationType;
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

        $mvrRegTypes = MvrRegistrationType::query()->get(['id', 'name']);

        foreach ($mvrRegTypes as $mvrRegType) {
            MvrColor::updateOrCreate([
                'mvr_registration_type_id' => $mvrRegType->id,
                'color' => 'White/Black',
                'name' => 'White/Black',
            ], [
                'mvr_registration_type_id' => $mvrRegType->id,
                'color' => 'White/Black',
                'name' => 'White/Black',
            ]);
        }
    }
}
