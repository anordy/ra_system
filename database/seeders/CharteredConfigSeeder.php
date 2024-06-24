<?php

namespace Database\Seeders;

use App\Models\PortConfig;
use App\Models\Returns\Chartered\CharteredReturnConfig;
use App\Models\TaxType;
use Illuminate\Database\Seeder;

class CharteredConfigSeeder extends Seeder
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
                'tax_type_code' => TaxType::CHARTERED_FLIGHT,
                'code' => 'NFAT',
                'name' => 'No. of foreign passengers (Airport Tax)',
                'row_type' => 'dynamic',
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate_usd' => 40,
                'active' => true,
            ],
            [
                'financial_year_id' => 1,
                'order' => 2,
                'tax_type_code' => TaxType::CHARTERED_FLIGHT,
                'code' => 'NLAT',
                'name' => 'No. of local passengers (Airport Tax)',
                'row_type' => 'dynamic',
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 10000,
                'active' => true,
            ],
            [
                'financial_year_id' => 1,
                'order' => 3,
                'tax_type_code' => TaxType::CHARTERED_FLIGHT,
                'code' => 'NFSF',
                'name' => 'No. of Foreign passengers (Safety Fee)',
                'row_type' => 'dynamic',
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate_usd' => 9,
                'active' => true,
                'value_calculated' => true,
                'value_formular' => 'NFAT',
            ],
            [
                'financial_year_id' => 1,
                'order' => 4,
                'tax_type_code' => TaxType::CHARTERED_FLIGHT,
                'code' => 'NLSF',
                'name' => 'No. of local passengers (Safety fee)',
                'row_type' => 'dynamic',
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 3000,
                'active' => true,
                'value_calculated' => true,
                'value_formular' => 'NLAT',
            ],
            [
                'financial_year_id' => 1,
                'order' => 5,
                'tax_type_code' => TaxType::CHARTERED_FLIGHT,
                'code' => 'IT',
                'name' => 'Infrastructure Tax',
                'row_type' => 'dynamic',
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 2000,
                'active' => true,
                'value_calculated' => true,
                'value_formular' => 'NLAT',
            ],
            [
                'financial_year_id' => 1,
                'order' => 6,
                'tax_type_code' => TaxType::CHARTERED_FLIGHT,
                'code' => 'TLATZS',
                'name' => 'Total Amount Fee Due(TZS)',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'formular' => 'NLAT+NLSF+IT',
                'active' => true,
            ],
            [
                'financial_year_id' => 1,
                'order' => 7,
                'tax_type_code' => TaxType::CHARTERED_FLIGHT,
                'code' => 'TLAUSD',
                'name' => 'Total Amount Fee Due (US$)',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'formular' => 'NFAT+NFSF',
                'active' => true,
            ],
            //   Seaport & Transport Tax
            [
                'financial_year_id' => 1,
                'order' => 8,
                'tax_type_code' => TaxType::CHARTERED_SEA,
                'code' => 'NFSP',
                'name' => 'No. of foreign passengers',
                'row_type' => 'dynamic',
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate_usd' => 10,
                'active' => true,
            ],
            [
                'financial_year_id' => 1,
                'order' => 9,
                'tax_type_code' => TaxType::CHARTERED_SEA,
                'code' => 'TLSUSD',
                'name' => 'Total Amount Fee Due (US$)',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'formular' => 'NFSP',
                'active' => true,
            ],

        ];

        foreach ($configs as $config) {
            CharteredReturnConfig::updateOrCreate([
                'code' => $config['code']
            ],$config);
        }
    }
}
