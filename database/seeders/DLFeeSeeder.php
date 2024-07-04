<?php

namespace Database\Seeders;

use App\Enum\DlFeeType;
use App\Models\DlFee;
use App\Models\DlLicenseDuration;
use Illuminate\Database\Seeder;

class DLFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $durations = [2 => 35000, 3 => 450000, 5 => 60000];

        foreach (DlFeeType::getConstants() as $type){
            foreach (DlLicenseDuration::all() as $duration) {
                DlFee::updateOrCreate([
                    'name' => $type,
                    'amount' => $durations[$duration->number_of_years],
                    'gfs_code' => 116101,
                    'type' => $type,
                    'dl_license_duration_id' => $duration->id
                ]);
            }
        }
    }
}
