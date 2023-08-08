<?php

namespace Database\Seeders;

use App\Models\Returns\ExciseDuty\MnoConfig;
use Illuminate\Database\Seeder;

class MnoConfigSeeder extends Seeder
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
                'value_label' => '01',
                'rate_label' => '02',
                'tax_label' => '03'
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
                'value_label' => '04',
                'rate_label' => '05',
                'tax_label' => '06'
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
                'value_label' => '07',
                'rate_label' => '08',
                'tax_label' => '09'
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
                'value_label' => 10,
                'rate_label' => 11,
                'tax_label' => 12
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
                'value_label' => 13,
                'rate_label' => 14,
                'tax_label' => 15
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

                'value_label' => 16,
                'rate_label' => 17,
                'tax_label' => 18
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
                'value_label' => 19,
                'rate_label' => 20,
                'tax_label' => 21
            ],
            [
                'financia_year_id' => 1,
                'order' => 8,
                'code' => 'IS',
                'name' => 'Imported services',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 12,
                'rate_usd' => 0,
                'active' => true,
                'value_label' => 22,
                'rate_label' => 23,
                'tax_label' => 24
            ],

            [
                'financia_year_id' => 1,
                'order' => 9,
                'code' => 'TOTAL',
                'name' => 'Total Excise Duty payable',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => 'MNOS+MVNOS+MCPRE+MCPOST+MM+OFS+OES+IS',
                'active' => true,
                'tax_label' => 25
            ],

        ];

        foreach ($configs as $config) {
            MnoConfig::updateOrCreate(['code' => $config['code']], $config);
        }
    }
}
