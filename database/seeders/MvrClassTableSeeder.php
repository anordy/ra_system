<?php

namespace Database\Seeders;

use App\Models\MvrClass;
use App\Models\MvrFuelType;
use App\Models\MvrMake;
use App\Models\MvrModel;
use Illuminate\Database\Seeder;

class MvrClassTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MvrClass::query()->updateOrcreate(['name' => 'A']);
        MvrClass::query()->updateOrcreate(['name' => 'B']);
    }

}
