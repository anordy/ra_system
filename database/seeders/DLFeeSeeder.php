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

        foreach ($types as $type){
            DlFee::create([
                'name' => $type,
                'amount' => 10000,
                'gfs_code' => 116101,
                'type' => $type
            ]);
        }
    }
}
