<?php

namespace Database\Seeders;

use App\Models\MvrBodyType;
use App\Models\MvrFuelType;
use App\Models\MvrMake;
use App\Models\MvrModel;
use Illuminate\Database\Seeder;

class MvrBodyTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MvrBodyType::query()->updateOrcreate(['name' => 'Sedan']);
        MvrBodyType::query()->updateOrcreate(['name' => 'Saloon']);
    }

}
