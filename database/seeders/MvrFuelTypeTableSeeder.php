<?php

namespace Database\Seeders;

use App\Models\MvrFuelType;
use App\Models\MvrMake;
use App\Models\MvrModel;
use Illuminate\Database\Seeder;

class MvrFuelTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MvrFuelType::query()->updateOrcreate(['name' => 'Petrol']);
        MvrFuelType::query()->updateOrcreate(['name' => 'Diesel']);
    }
}
