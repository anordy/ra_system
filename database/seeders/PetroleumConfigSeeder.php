<?php

namespace Database\Seeders;

use App\Models\Returns\PetroleumConfig;
use Illuminate\Database\Seeder;

class PetroleumConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configs = [
            [
                'financia_year_id' => 1,
                'order' => 1,
                'code' => 'MSP',
                'name' => 'MSP Petroleum',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 350,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 2,
                'code' => 'GO',
                'name' => 'GO Petroleum',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 350,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 3,
                'code' => 'IK',
                'name' => 'IK Petroleum',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 12.80,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 5,
                'code' => 'JET',
                'name' => 'Jet A-1',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate' => 0,
                'rate_usd' => 1,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 5,
                'code' => 'TOTAL',
                'name' => 'Total Petroleum Levy',
                'row_type' => 'unremovable',
                'col_type' => 'subtotal',
                'value_calculated' => true,
                'formular' => 'MSP+GO+IK+JET',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 6,
                'code' => 'JET',
                'name' => 'Jet A-1',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate' => 0,
                'rate_usd' => 1,
                'active' => true,
            ],
        ];

        foreach ($configs as $config) {
            PetroleumConfig::updateOrCreate($config);
        }
    }
}
