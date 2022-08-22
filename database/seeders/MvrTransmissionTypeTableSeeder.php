<?php

namespace Database\Seeders;

use App\Models\MvrFuelType;
use App\Models\MvrMake;
use App\Models\MvrModel;
use App\Models\MvrTransmissionType;
use Illuminate\Database\Seeder;

class MvrTransmissionTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MvrTransmissionType::query()->updateOrcreate(['name' => 'Automatic']);
        MvrTransmissionType::query()->updateOrcreate(['name' => 'Manual']);
        MvrTransmissionType::query()->updateOrcreate(['name' => 'Electric']);
        MvrTransmissionType::query()->updateOrcreate(['name' => 'Other']);
    }
}
