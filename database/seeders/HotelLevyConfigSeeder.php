<?php

namespace Database\Seeders;

use App\Models\HotelLevyConfig;
use Illuminate\Database\Seeder;

class HotelLevyConfigSeeder extends Seeder
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
                'taxtype_id' => 2
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
                'rate_usd' => 8,
                'active' => true,
                'taxtype_id' => 2
            ],
            [
                'financia_year_id' => 1,
                'order' => 3,
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
                'taxtype_id' => 3
            ],
            [
                'financia_year_id' => 1,
                'order' => 4,
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
                'taxtype_id' => 4
            ],
            [
                'financia_year_id' => 1,
                'order' => 5,
                'code' => 'OS',
                'name' => 'Other Supplies',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => false,
                'rate_type' => 'percentage',
                'rate' => 12,
                'currency' => 'TZS',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 6,
                'code' => 'LP',
                'name' => 'Local Purchases',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => false,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 7,
                'code' => 'IP',
                'name' => 'Imports Purchases',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => false,
                'rate_type' => 'percentage',
                'currency' => 'TZS',
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 8,
                'code' => 'IT',
                'name' => 'Infrastructure Tax',
                'row_type' => 'dynamic',
                'value_calculated' => false,
                'col_type' => 'normal',
                'rate_applicable' => true,
                'rate_type' => 'fixed',
                'currency' => 'USD',
                'rate_usd' => 1,
                'active' => true,
            ],
            [
                'financia_year_id' => 1,
                'order' => 9,
                'code' => 'TOTAL_HL',
                'name' => 'Total Levy Amount Due (Hotel Levy)',
                'row_type' => 'unremovable',
                'col_type' => 'subtotal',
                'value_calculated' => true,
                'formular' => 'HS+NOBN+OS+LP+IP+IT',
                'active' => true,
                'taxtype_id' => 2
            ],
            [
                'financia_year_id' => 1,
                'order' => 10,
                'code' => 'TOTAL_RL',
                'name' => 'Total Levy Amount Due (Restaurant Levy)',
                'row_type' => 'unremovable',
                'col_type' => 'subtotal',
                'value_calculated' => true,
                'formular' => 'RS+OS+LP+IP',
                'active' => true,
                'taxtype_id' => 3
            ],
            [
                'financia_year_id' => 1,
                'order' => 11,
                'code' => 'TOTAL_TOS',
                'name' => 'Total Levy Amount Due (Tour Operating Levy)',
                'row_type' => 'unremovable',
                'col_type' => 'subtotal',
                'value_calculated' => true,
                'formular' => 'TOS+OS+LP+IP',
                'active' => true,
                'taxtype_id' => 4
            ],
        ];

        foreach ($configs as $config) {
            HotelLevyConfig::updateOrCreate($config);
        }
    }
}
