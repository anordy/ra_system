<?php

namespace Database\Seeders;

use App\Models\BusinessActivity;
use App\Models\BusinessType;
use Illuminate\Database\Seeder;

class BusinessActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BusinessActivity::updateOrCreate(['name' => 'Wholesale', 'business_type' => BusinessType::OTHER]);
        BusinessActivity::updateOrCreate(['name' => 'Retailer', 'business_type' => BusinessType::OTHER]);
        BusinessActivity::updateOrCreate(['name' => 'Short Term Accommodation', 'business_type' => BusinessType::HOTEL]);
        BusinessActivity::updateOrCreate(['name' => 'Long Term Accommodation', 'business_type' => BusinessType::HOTEL]);
        BusinessActivity::updateOrCreate(['name' => 'Restaurant', 'business_type' => BusinessType::HOTEL]);
        BusinessActivity::updateOrCreate(['name' => 'Bar', 'business_type' => BusinessType::HOTEL]);
        BusinessActivity::updateOrCreate(['name' => 'Accommodation & Restaurant', 'business_type' => BusinessType::HOTEL]);
        BusinessActivity::updateOrCreate(['name' => 'Restaurant & Bar', 'business_type' => BusinessType::HOTEL]);
        BusinessActivity::updateOrCreate(['name' => 'Accommodation, Restaurant & Bar', 'business_type' => BusinessType::HOTEL]);
        BusinessActivity::updateOrCreate(['name' => 'Other Services', 'business_type' => BusinessType::HOTEL]);
    }
}
