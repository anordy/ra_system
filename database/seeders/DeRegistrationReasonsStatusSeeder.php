<?php

namespace Database\Seeders;

use App\Enum\MvrDeRegistrationReasonStatus;
use App\Models\MvrDeRegistrationReason;
use Illuminate\Database\Seeder;

class DeRegistrationReasonsStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (MvrDeRegistrationReasonStatus::getConstants() as $reason) {
            MvrDeRegistrationReason::create(['name' => $reason]);
        }
    }
}
