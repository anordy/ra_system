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
        HotelLevyConfig::create([
            'name' => 'Hotel Supplies',
            'code' => 'HS',
            'is_rate_in_percentage' => true,
            'rate_in_percentage' => 12,
            'financial_year' => 2022
        ]);

        HotelLevyConfig::create([
            'name' => 'No of Bed Nights',
            'code' => 'NOB',
            'is_rate_in_percentage' => false,
            'rate_in_amount' => 8,
            'financial_year' => 2022
        ]);

        HotelLevyConfig::create([
            'name' => 'Restaurant Supplies',
            'code' => 'RS',
            'is_rate_in_percentage' => true,
            'rate_in_percentage' => 12,
            'financial_year' => 2022
        ]);

        HotelLevyConfig::create([
            'name' => 'Other Supplies',
            'code' => 'OS',
            'is_rate_in_percentage' => true,
            'rate_in_percentage' => 12,
            'financial_year' => 2022
        ]);

        HotelLevyConfig::create([
            'name' => 'Tour Operation Services',
            'code' => 'TOS',
            'is_rate_in_percentage' => true,
            'rate_in_percentage' => 12,
            'financial_year' => 2022
        ]);

        HotelLevyConfig::create([
            'name' => 'Local Purchases',
            'code' => 'LP',
            'is_rate_in_percentage' => false,
            'financial_year' => 2022
        ]);

        HotelLevyConfig::create([
            'name' => 'Imports Purchases',
            'code' => 'IP',
            'is_rate_in_percentage' => false,
            'financial_year' => 2022
        ]);

        HotelLevyConfig::create([
            'name' => 'Infrastructure Tax',
            'code' => 'IT',
            'is_rate_in_percentage' => false,
            'rate_in_amount' => 8,
            'financial_year' => 2022
        ]);
    }
}
