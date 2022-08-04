<?php

namespace Database\Seeders;

use App\Models\Returns\MmTransferConfig;
use Illuminate\Database\Seeder;

class MmTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $configs = [
            [
                'financia_year_id' => 1,
                'order' => 1,
                'code' => 'LMTRANSFER',
                'name' => 'Amount Of Levy On Money Transfer',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => false,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 1,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 1,
                'code' => 'LWITHDRAWALS',
                'name' => 'Amount Of Levy On Withdrawals',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => false,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 1,
                'rate_usd' => 0,
                'active' => true,
            ],
            [ 
                'financia_year_id' => 1,
                'order' => 12,
                'code' => 'TotalEMT',
                'name' => 'Total Amount Of Levy Collected',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => 'LMTRANSFER+LWITHDRAWALS',
                'active' => true,
            ],
        ];

        foreach ($configs as $config) {
            MmTransferConfig::updateOrCreate($config);
        }
    }
}
