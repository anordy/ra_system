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
        MvrColor::query()->updateOrcreate(['name' => 'White','hex_value'=>'#ffffff']);
        MvrColor::query()->updateOrcreate(['name' => 'Red','hex_value'=>'#ff0000']);
        MvrColor::query()->updateOrcreate(['name' => 'Blue','hex_value'=>'#0000ff']);
        MvrColor::query()->updateOrcreate(['name' => 'Silver','hex_value'=>'#cccccc']);
    }
}
