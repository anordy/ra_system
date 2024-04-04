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
        MvrFeeType::query()->updateOrcreate(['type' => MvrFeeType::TYPE_REGISTRATION]);
        MvrFeeType::query()->updateOrcreate(['type' => MvrFeeType::TYPE_DE_REGISTRATION]);
        MvrFeeType::query()->updateOrcreate(['type' => MvrFeeType::TYPE_CHANGE_REGISTRATION]);
        MvrFeeType::query()->updateOrcreate(['type' => MvrFeeType::STATUS_CHANGE]);
        MvrFeeType::query()->updateOrcreate(['type' => MvrFeeType::TRANSFER_OWNERSHIP]);
    }
}
