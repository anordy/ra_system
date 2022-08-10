<?php

namespace Database\Seeders;

use App\Models\PortConfig;
use App\Models\TaxType;
use Illuminate\Database\Seeder;

class PortConfigSeeder extends Seeder
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
                'tax_type_code' => TaxType::AIRPORT_SERVICE_SAFETY_FEE,
                'code' => 'NFAT',
                'name' => 'No. of foreign passengers (Airport Tax)',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate_usd' => 40,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 2,
                'tax_type_code' => TaxType::AIRPORT_SERVICE_SAFETY_FEE,
                'code' => 'NLAT',
                'name' => 'No. of local passengers (Airport Tax)',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 10000,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 3,
                'tax_type_code' => TaxType::AIRPORT_SERVICE_SAFETY_FEE,
                'code' => 'NFSF',
                'name' => 'No. of Foreign passengers (Safety Fee)',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate_usd' => 9,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 4,
                'tax_type_code' => TaxType::AIRPORT_SERVICE_SAFETY_FEE,
                'code' => 'NLSF',
                'name' => 'No. of local passengers (Safety fee)',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 3000,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 5,
                'tax_type_code' => TaxType::AIRPORT_SERVICE_SAFETY_FEE,
                'code' => 'IT',
                'name' => 'Imfrastracture Tax',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 2000,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 6,
                'tax_type_code' => TaxType::AIRPORT_SERVICE_SAFETY_FEE,
                'code' => 'TLATZS',
                'name' => 'Total Amount Fee Due(TZS)',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => 'NLAT+NLSF+IT',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 7,
                'tax_type_code' => TaxType::AIRPORT_SERVICE_SAFETY_FEE,
                'code' => 'TLAUSD',
                'name' => 'Total Amount Fee Due (US$)',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => 'NFAT+NFSF',
                'active' => true,
            ],
            //   Sea Port & Transport Tax
            [
                'financia_year_id' => 1,
                'order' => 8,
                'tax_type_code' => TaxType::SEA_SERVICE_TRANSPORT_CHARGE,
                'code' => 'NFSP',
                'name' => 'No. of foreign passengers',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate_usd' => 10,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 9,
                'tax_type_code' => TaxType::SEA_SERVICE_TRANSPORT_CHARGE,
                'code' => 'NLTM',
                'name' => 'No. of local passengers(ZNZ - T/M)',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 2000,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 10,
                'tax_type_code' => TaxType::SEA_SERVICE_TRANSPORT_CHARGE,
                'code' => 'NLZNZ',
                'name' => 'No. of local passengers (ZNZ - ZNZ)',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 1000,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 11,
                'tax_type_code' => TaxType::SEA_SERVICE_TRANSPORT_CHARGE,
                'code' => 'ITZNZ',
                'name' => 'Imfrastructure Tax (ZNZ - ZNZ)',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 1000,
                'rate_usd' => 0,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 12,
                'tax_type_code' => TaxType::SEA_SERVICE_TRANSPORT_CHARGE,
                'code' => 'ITTM',
                'name' => 'Imfrastructure Tax (ZNZ - T/M)',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'TZS',
                'rate' => 2000,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 13,
                'tax_type_code' => TaxType::SEA_SERVICE_TRANSPORT_CHARGE,
                'code' => 'NSUS',
                'name' => 'Value of Net Sales (US$)',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 8,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 14,
                'tax_type_code' => TaxType::SEA_SERVICE_TRANSPORT_CHARGE,
                'code' => 'NSTZ',
                'name' => 'Value of Net Sales (TZS)',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 8,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 15,
                'tax_type_code' => TaxType::SEA_SERVICE_TRANSPORT_CHARGE,
                'code' => 'TLSTZS',
                'name' => 'Total Amount Fee Due(TZS)',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => 'NLTM+NLZNZ+ITZNZ+ITTM+NSTZ',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 16,
                'tax_type_code' => TaxType::SEA_SERVICE_TRANSPORT_CHARGE,
                'code' => 'TLSUSD',
                'name' => 'Total Amount Fee Due (US$)',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true, 
                'formular' => 'NFSP+NSUS',
                'active' => true,
            ],
        ];

        foreach ($configs as $config) {
            PortConfig::updateOrCreate($config);
        }

    }
}
