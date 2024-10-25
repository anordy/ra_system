<?php

namespace Database\Seeders;

use App\Models\Ntr\Returns\NtrVatReturnConfig;
use Illuminate\Database\Seeder;

class NtrVatReturnConfigSeeder extends Seeder
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
                'financial_year_id' => 1,
                'order' => 1,
                'code' => 'SRS',
                'name' => 'Standard Rated Services',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'USD',
                'rate' => 18,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financial_year_id' => 1,
                'order' => 2,
                'code' => 'TOTAL',
                'name' => 'Total Payable Amount',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => 'SRS',
                'active' => true,
            ],
        ];

        foreach ($configs as $config) {
            NtrVatReturnConfig::updateOrCreate(['code' => $config['code']], $config);
        }
    }
}
