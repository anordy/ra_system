<?php

namespace Database\Seeders;

use App\Models\Returns\Vat\VatReturnConfig;
use Illuminate\Database\Seeder;

class VatReturnConfigSeeder extends Seeder
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
                'financial_year_id' => 1,
                'order' => 1,
                'code' => 'SRS',
                'name' => 'Standard Rated Supplies',
                'vat_service_code'=>'SUP',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 15,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financial_year_id' => 1,
                'order' => 2,
                'code' => 'ZRS',
                'name' => 'Zero Rated Supplies',
                'vat_service_code'=>'SUP',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 0,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financial_year_id' => 1,
                'order' => 3,
                'code' => 'ES',
                'name' => 'Exempt Supplies',
                'vat_service_code'=>'SUP',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 0,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financial_year_id' => 1,
                'order' => 4,
                'code' => 'SR',
                'name' => 'Special Relief',
                'vat_service_code'=>'SUP',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 0,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financial_year_id' => 1,
                'order' => 5,
                'code' => 'EIP',
                'name' => 'Exempt Import Purchases',
                'vat_service_code'=>'PUR',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 0,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financial_year_id' => 1,
                'order' => 6,
                'code' => 'ELP',
                'name' => 'Exempt Local Purchases',
                'vat_service_code'=>'PUR',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 0,
                'rate_usd' => 0,
                'active' => true,
            ],

            [
                'financial_year_id' => 1,
                'order' => 7,
                'code' => 'NCP',
                'name' => 'Non Credible Purchases',
                'vat_service_code'=>'PUR',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 0,
                'rate_usd' => 0,
                'active' => true,
            ],

            [
                'financial_year_id' => 1,
                'order' => 8,
                'code' => 'VDP',
                'name' => 'Vat Differed Purchases',
                'vat_service_code'=>'PUR',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 0,
                'rate_usd' => 0,
                'active' => true,
            ],

            [
                'financial_year_id' => 1,
                'order' => 9,
                'code' => 'SLP',
                'name' => 'Standard Local and Imports Purchases',
                'vat_service_code'=>'PUR',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 15,
                'rate_usd' => 0,
                'active' => true,
            ],

            [
                'financial_year_id' => 1,
                'order' => 10,
                'code' => 'SRI',
                'name' => 'Standard Purchases from  T/Mainland',
                'vat_service_code'=>'PUR',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 18,
                'rate_usd' => 0,
                'active' => true,
            ],

            [
                'financial_year_id' => 1,
                'order' => 11,
                'code' => 'TIT',
                'name' => 'Total Input Tax',
                'vat_service_code'=>'PUR',
                'row_type' => 'unremovable',
                'col_type' => 'subtotal',
                'value_calculated' => true,
                'formular' => 'SLP+SRI',
                'active' => true,
            ],

            [
                'financial_year_id' => 1,
                'order' => 12,
                'code' => 'TVPR',
                'name' => 'Total Vat Payable/Refundable',
                'vat_service_code'=>'PUR',
                'row_type' => 'unremovable',
                'col_type' => 'subtotal',
                'value_calculated' => true,
                'formular' => 'SRS-SLP+SRI',
                'active' => true,
            ],

            [
                'financial_year_id' => 1,
                'order' => 13,
                'code' => 'VWH',
                'name' => 'Vat Withheld',
                'vat_service_code'=>'PUR',
                'row_type' => 'unremovable',
                'value_calculated' => false,
                'col_type' => 'external',
                'currency' => 'TZS',
                'active' => true,
            ],

            [
                'financial_year_id' => 1,
                'order' => 14,
                'code' => 'CBF',
                'name' => 'Vat Credit Brought Forward',
                'vat_service_code'=>'PUR',
                'row_type' => 'unremovable',
                'value_calculated' => false,
                'col_type' => 'normal',
                'currency' => 'TZS',
                'active' => true,
            ],


            [
                'financial_year_id' => 1,
                'order' => 15,
                'code' => 'IT',
                'name' => 'Infrastructure Tax',
                'vat_service_code'=>'PUR',
                'row_type' => 'unremovable',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 2,
                'rate_usd' => 0,
                'active' => true,
            ],

            [
                'financial_year_id' => 1,
                'order' => 16,
                'code' => 'VAD',
                'name' => 'Total Vat Amount Due',
                'vat_service_code'=>'PUR',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => 'SRS-SLP+SRI-VWH',
                'active' => true,
            ],


        ];

        foreach ($configs as $config) {
            VatReturnConfig::query()->updateOrCreate($config);
        }
    }
}
