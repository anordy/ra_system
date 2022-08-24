<?php

namespace Database\Seeders;

use App\Models\MvrFeeType;
use App\Models\MvrFuelType;
use App\Models\MvrMake;
use App\Models\MvrModel;
use App\Models\MvrTransmissionType;
use Illuminate\Database\Seeder;

class MvrFeeTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MvrFeeType::query()->updateOrcreate(['type' => 'Registration']);
        MvrFeeType::query()->updateOrcreate(['type' => 'De-Registration']);
        MvrFeeType::query()->updateOrcreate(['type' => 'Registration Change']);
    }
}
