<?php

namespace Database\Seeders;

use App\Models\Returns\ExciseDuty\MNOConfig;
use Illuminate\Database\Seeder;

class MNOConfigSeeder extends Seeder
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
                'code' => 'MNOS',
                'name' => 'Mobile Network Operators Services - MNO',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 17,
                'rate_usd' => 0,
                'active' => true,
            ],

            [
                'financia_year_id' => 1,
                'order' => 2,
                'code' => 'MVNOS',
                'name' => 'Mobile Virtual Network Operators - MVNO',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 17,
                'rate_usd' => 0,
                'active' => true,
            ],

            [
                'financia_year_id' => 1,
                'order' => 3,
                'code' => 'MCPRE',
                'name' => 'Mobile cellular pre-paid',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 17,
                'rate_usd' => 0,
                'active' => true,
            ],

            [
                'financia_year_id' => 1,
                'order' => 4,
                'code' => 'MCPOST',
                'name' => 'Mobile cellular post-paid',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 17,
                'rate_usd' => 0,
                'active' => true,
            ],

            [
                'financia_year_id' => 1,
                'order' => 5,
                'code' => 'MM',
                'name' => 'Mobile Money (Money Transfer)',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 10,
                'rate_usd' => 0,
                'active' => true,
            ],

            [
                'financia_year_id' => 1,
                'order' => 6,
                'code' => 'OFS',
                'name' => 'Other financial services',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 10,
                'rate_usd' => 0,
                'active' => true,
            ],

            [
                'financia_year_id' => 1,
                'order' => 7,
                'code' => 'OES',
                'name' => 'Other excisable services',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 17,
                'rate_usd' => 0,
                'active' => true,
            ],

            [
                'financia_year_id' => 1,
                'order' => 8,
                'code' => 'TOTAL',
                'name' => 'Total Excise Duty payable',
                'row_type' => 'unremovable',
                'col_type' => 'subtotal',
                'value_calculated' => true,
                'formular' => 'MNOS+MVNOS+MCPRE+MCPOST+MM+OFS+OES',
                'active' => true,
            ],

        ];

        foreach ($configs as $config) {
            MNOConfig::updateOrCreate($config);
        }
    }
}
