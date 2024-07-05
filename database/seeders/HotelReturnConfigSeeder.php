<?php

namespace Database\Seeders;

use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\TaxType;
use Illuminate\Database\Seeder;

class HotelReturnConfigSeeder extends Seeder
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
                'code' => 'HS',
                'name' => 'Hotel Supplies',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 12,
                'rate_usd' => 0,
                'active' => true,
                'tax_type_code' => TaxType::HOTEL,
                'heading_type' => 'supplies'
            ],
            [
                'financia_year_id' => 1,
                'order' => 2,
                'code' => 'NOBN',
                'name' => 'No of Bed Nights',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate_usd' => 0,
                'active' => true,
                'tax_type_code' => TaxType::HOTEL,
                'heading_type' => 'supplies'
            ],
            [
                'financia_year_id' => 1,
                'order' => 3,
                'code' => 'HSBNB',
                'name' => 'Hotel Supplies',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 15,
                'rate_usd' => 0,
                'active' => true,
                'tax_type_code' => TaxType::AIRBNB,
                'heading_type' => 'supplies'
            ],
            [
                'financia_year_id' => 1,
                'order' => 4,
                'code' => 'NOBNBNB',
                'name' => 'No of Bed Nights',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate_usd' => 0,
                'active' => true,
                'tax_type_code' => TaxType::AIRBNB,
                'heading_type' => 'supplies'
            ],
            [
                'financia_year_id' => 1,
                'order' => 5,
                'code' => 'RS',
                'name' => 'Restaurant Supplies',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 12,
                'rate_usd' => 0,
                'active' => true,
                'tax_type_code' => TaxType::RESTAURANT,
                'heading_type' => 'supplies'
            ],
            [
                'financia_year_id' => 1,
                'order' => 6,
                'code' => 'TOS',
                'name' => 'Tour Operation Services',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'rate' => 12,
                'rate_usd' => 1,
                'active' => true,
                'tax_type_code' => TaxType::TOUR_OPERATOR,
                'heading_type' => 'supplies'
            ],
            [
                'financia_year_id' => 1,
                'order' => 7,
                'code' => 'OS',
                'name' => 'Other Supplies',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'rate' => 12,
                'currency' => 'TZS',
                'active' => true,
                'heading_type' => 'supplies'
            ],
            [
                'financia_year_id' => 1,
                'order' => 8,
                'code' => 'OSBNB',
                'name' => 'Other Supplies',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'percentage',
                'rate' => 15,
                'currency' => 'TZS',
                'active' => true,
                'tax_type_code' => TaxType::AIRBNB,
                'heading_type' => 'supplies'
            ],
            [
                'financia_year_id' => 1,
                'order' => 9,
                'code' => 'LP',
                'name' => 'Local Purchases',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => false,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'active' => true,
                'heading_type' => 'purchases'
            ],
            [
                'financia_year_id' => 1,
                'order' => 10,
                'code' => 'IP',
                'name' => 'Imports Purchases',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => false,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'active' => true,
                'heading_type' => 'purchases'
            ],
            [
                'financia_year_id' => 1,
                'order' => 11,
                'code' => 'IT',
                'name' => 'Infrastructure Tax',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'value_formular' => 'NOBN',
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate_usd' => 1,
                'active' => true,
                'heading_type' => 'purchases',
                'tax_type_code' => TaxType::HOTEL,
            ],
            [
                'financia_year_id' => 1,
                'order' => 11,
                'code' => 'IT',
                'name' => 'Infrastructure Tax',
                'row_type' => 'dynamic',
                'value_calculated' => true,
                'value_formular' => 'NOBNBNB',
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate_usd' => 1,
                'active' => true,
                'heading_type' => 'purchases',
                'tax_type_code' => TaxType::AIRBNB,
            ],
            [
                'financia_year_id' => 1,
                'order' => 12,
                'code' => 'LW',
                'name' => 'Less withheld Tax',
                'row_type' => 'unremovable',
                'col_type' => 'normal',
                'value_calculated' => false,
                'active' => true,
                'tax_type_code' => TaxType::HOTEL,
                'rate_applicable' => false,
                'heading_type' => 'purchases'
            ],
            [
                'financia_year_id' => 1,
                'order' => 12,
                'code' => 'LWBNB',
                'name' => 'Less withheld Tax',
                'row_type' => 'unremovable',
                'col_type' => 'normal',
                'value_calculated' => false,
                'active' => true,
                'tax_type_code' => TaxType::AIRBNB,
                'rate_applicable' => false,
                'heading_type' => 'purchases'
            ],
            [
                'financia_year_id' => 1,
                'order' => 13,
                'code' => 'TOTAL_PAX',
                'name' => 'Total Pax',
                'row_type' => 'unremovable',
                'col_type' => 'hotel_top',
                'active' => true,
                'tax_type_code' => TaxType::HOTEL,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 14,
                'code' => 'SINGLE_ROOM',
                'name' => 'Charge per Single room',
                'row_type' => 'unremovable',
                'col_type' => 'hotel_bottom',
                'active' => true,
                'tax_type_code' => TaxType::HOTEL,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 15,
                'code' => 'DOUBLE_ROOM',
                'name' => 'Charge per Double room',
                'row_type' => 'unremovable',
                'col_type' => 'hotel_bottom',
                'active' => true,
                'tax_type_code' => TaxType::HOTEL,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 16,
                'code' => 'TRIPPLE_ROOM',
                'name' => 'Charge per Tripple room',
                'row_type' => 'unremovable',
                'col_type' => 'hotel_bottom',
                'active' => true,
                'tax_type_code' => TaxType::HOTEL,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 17,
                'code' => 'OTHER_ROOM',
                'name' => 'Charge per Other room',
                'row_type' => 'unremovable',
                'col_type' => 'hotel_bottom',
                'active' => true,
                'tax_type_code' => TaxType::HOTEL,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 13,
                'code' => 'TOTAL_PAX',
                'name' => 'Total Pax',
                'row_type' => 'unremovable',
                'col_type' => 'hotel_top',
                'active' => true,
                'tax_type_code' => TaxType::AIRBNB,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 14,
                'code' => 'SINGLE_ROOM',
                'name' => 'Charge per Single room',
                'row_type' => 'unremovable',
                'col_type' => 'hotel_bottom',
                'active' => true,
                'tax_type_code' => TaxType::AIRBNB,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 15,
                'code' => 'DOUBLE_ROOM',
                'name' => 'Charge per Double room',
                'row_type' => 'unremovable',
                'col_type' => 'hotel_bottom',
                'active' => true,
                'tax_type_code' => TaxType::AIRBNB,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 16,
                'code' => 'TRIPPLE_ROOM',
                'name' => 'Charge per Tripple room',
                'row_type' => 'unremovable',
                'col_type' => 'hotel_bottom',
                'active' => true,
                'tax_type_code' => TaxType::AIRBNB,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 17,
                'code' => 'OTHER_ROOM',
                'name' => 'Charge per Other room',
                'row_type' => 'unremovable',
                'col_type' => 'hotel_bottom',
                'active' => true,
                'tax_type_code' => TaxType::AIRBNB,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 18,
                'code' => 'TOTAL_HL',
                'name' => 'Total Levy Amount Due (Hotel Levy)',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => '(HS+OS+LP+IP)-LW',
                'active' => true,
                'tax_type_code' => TaxType::HOTEL,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 19,
                'code' => 'TOTAL_RL',
                'name' => 'Total Levy Amount Due (Restaurant Levy)',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => '(RS+OS+LP+IP)-LWRS',
                'active' => true,
                'tax_type_code' => TaxType::RESTAURANT,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 20,
                'code' => 'TOTAL_TOS',
                'name' => 'Total Levy Amount Due (Tour Operating Levy)',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => 'TOS+OS+LP+IP',
                'active' => true,
                'tax_type_code' => TaxType::TOUR_OPERATOR,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 21,
                'code' => 'TOTAL_HLBNB',
                'name' => 'Total Levy Amount Due (Hotel Airbnb)',
                'row_type' => 'unremovable',
                'col_type' => 'total',
                'value_calculated' => true,
                'formular' => '(HSBNB+OSBNB+LP+IP)-LWBNB',
                'active' => true,
                'tax_type_code' => TaxType::AIRBNB,
                'rate_applicable' => false,
            ],
            [
                'financia_year_id' => 1,
                'order' => 12,
                'code' => 'LWRS',
                'name' => 'Less withheld Tax',
                'row_type' => 'unremovable',
                'col_type' => 'normal',
                'value_calculated' => false,
                'active' => true,
                'tax_type_code' => TaxType::RESTAURANT,
                'rate_applicable' => false,
                'heading_type' => 'purchases'
            ],
        ];

        foreach ($configs as $config) {
            HotelReturnConfig::updateOrCreate(['code' => $config['code'], 'tax_type_code' => $config['tax_type_code'] ?? 'NA'],$config);
        }
    }
}
