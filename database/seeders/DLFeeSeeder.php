<?php

namespace Database\Seeders;

use App\Models\DlFee;
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
        $types = ['FRESH', 'RENEW', 'DUPLICATE'];
        $durations = [1, 2];

        foreach ($types as $type){
            foreach ($durations as $duration) {
                DlFee::updateOrCreate([
                    'name' => $type,
                    'amount' => 10000,
                    'gfs_code' => 116101,
                    'type' => $type,
                    'dl_licence_duration_id' => $duration
                ]);
            }
        }
    }
}
