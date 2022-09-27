<?php

namespace Database\Seeders;

use App\Models\MvrFeeType;
use App\Models\MvrFuelType;
use App\Models\MvrMake;
use App\Models\MvrModel;
use App\Models\MvrOwnershipTransferReason;
use App\Models\MvrTransmissionType;
use Illuminate\Database\Seeder;

class MvrTransferReasonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MvrOwnershipTransferReason::query()->updateOrcreate(['name' => MvrOwnershipTransferReason::TRANSFER_REASON_SOLD]);
        MvrOwnershipTransferReason::query()->updateOrcreate(['name' => MvrOwnershipTransferReason::TRANSFER_REASON_OTHER]);
    }
}
