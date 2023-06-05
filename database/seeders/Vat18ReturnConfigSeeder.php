<?php

namespace Database\Seeders;

use App\Models\Returns\Vat\Vat18ReturnConfig;
use Illuminate\Database\Seeder;

class Vat18ReturnConfigSeeder extends Seeder
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
                'rate' => 18,
                'rate_usd' => 0,
                'active' => true,
                'value_label' => '02',
                'rate_label' => '03',
                'tax_label' => '04'
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
                'value_label' => '05',
                'rate_label' => '06',
                'tax_label' => '07',
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
                'value_label' => '08',
            ],
            [
                'financial_year_id' => 1,
                'order' => 4,
                'code' => 'SER',
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
                'value_label' => '09',
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
                'value_label' => '10',
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
                'value_label' => '11',
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
                'value_label' => '12',
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
                'value_label' => '13',
            ],

            [
                'financial_year_id' => 1,
                'order' => 9,
                'code' => 'SLP',
                'name' => 'Standard Local Purchases',
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
                'value_label' => '14',
                'rate_label' => 15,
                'tax_label' => 16
            ],

            [
                'financial_year_id' => 1,
                'order' => 10,
                'code' => 'IP',
                'name' => 'Imports Purchases',
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
                'value_label' => 17,
                'rate_label' => 18,
                'tax_label' => 19
            ],

            [
                'financial_year_id' => 1,
                'order' => 11,
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
                'value_label' => '20',
                'rate_label' => 21,
                'tax_label' => 22
            ],

            [
                'financial_year_id' => 1,
                'order' => 12,
                'code' => 'SA',
                'name' => 'Schedule A (Total Tax on Purchases)',
                'vat_service_code'=>'PUR',
                'row_type' => 'dynamic',
                'col_type' => 'exemptedMethodTwo',
                'value_calculated' => false,
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 15,
                'rate_usd' => 0,
                'active' => true,
                'value_label' => 23,
                'rate_label' => 24,
                'tax_label' => 25
            ],

            [
                'financial_year_id' => 1,
                'order' => 13,
                'code' => 'SC',
                'name' => 'Schedule C (Calculated Tax with Exemption)',
                'vat_service_code'=>'PUR',
                'row_type' => 'dynamic',
                'col_type' => 'exemptedMethodTwo',
                'value_calculated' => false,
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 15,
                'rate_usd' => 0,
                'active' => true,
                'value_label' => 26,
                'rate_label' => 27,
                'tax_label' => 28
            ],

            [
                'financial_year_id' => 1,
                'order' => 14,
                'code' => 'TIT',
                'name' => 'Total Input Tax',
                'vat_service_code'=>'PUR',
                'row_type' => 'unremovable',
                'col_type' => 'subtotal',
                'value_calculated' => true,
                'formular' => 'SLP+SRI+IP',
                'active' => true,
                'tax_label' => 29
            ],

            [
                'financial_year_id' => 1,
                'order' => 15,
                'code' => 'TITM1',
                'name' => 'Total Input Tax',
                'vat_service_code'=>'PUR',
                'row_type' => 'unremovable',
                'col_type' => 'exemptedMethodOne',
                'value_calculated' => true,
                'formular' => '(SRS+ZRS+SER)/(SRS+ZRS+ES+SER)',
                'active' => true,
                'tax_label' => 30
            ],

            [
                'financial_year_id' => 1,
                'order' => 16,
                'code' => 'TVPR',
                'name' => 'Total Vat Payable/Refundable',
                'vat_service_code'=>'PUR',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => 'SRS-SLP-IP-SRI',
                'active' => true,
                'tax_label' => 31
            ],

            [
                'financial_year_id' => 1,
                'order' => 17,
                'code' => 'VWH',
                'name' => 'Vat Withheld',
                'vat_service_code'=>'PUR',
                'row_type' => 'unremovable',
                'value_calculated' => false,
                'col_type' => 'external',
                'currency' => 'TZS',
                'active' => true,
                'tax_label' => 32
            ],

            [
                'financial_year_id' => 1,
                'order' => 18,
                'code' => 'CBF',
                'name' => 'Vat Credit Brought Forward',
                'vat_service_code'=>'PUR',
                'row_type' => 'unremovable',
                'value_calculated' => false,
                'col_type' => 'external',
                'currency' => 'TZS',
                'active' => true,
                'tax_label' => 33
            ],


            [
                'financial_year_id' => 1,
                'order' => 19,
                'code' => 'ITE',
                'name' => 'Infrastructure Tax (Electricity)',
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
                'value_label' => '34',
                'rate_label' => 35,
                'tax_label' => 36
            ],

            [
                'financial_year_id' => 1,
                'order' => 20,
                'code' => 'ITH',
                'name' => 'Infrastructure Tax (Hotel)',
                'vat_service_code'=>'PUR',
                'row_type' => 'unremovable',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate' => 1,
                'rate_usd' => 0,
                'active' => true,
                'value_label' => '37',
                'rate_label' => 38,
                'tax_label' => 39
            ],

            [
                'financial_year_id' => 1,
                'order' => 21,
                'code' => 'VAD',
                'name' => 'Total Vat Amount Due',
                'vat_service_code'=>'PUR',
                'row_type' => 'unremovable',
                'col_type' => 'grandTotal',
                'value_calculated' => true,
                'formular' => 'SRS-SLP-IP-SRI-VWH-CBF',
                'active' => true,
                'tax_label' => 40
            ],


        ];

        foreach ($configs as $config) {
            Vat18ReturnConfig::query()->updateOrCreate([
                'code' => $config['code']
            ], $config);
        }
    }
}
