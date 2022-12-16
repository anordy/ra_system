<?php

namespace Database\Seeders;

use App\Models\BusinessActivity;
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
        BusinessActivity::updateOrCreate(['name' => 'Wholesale']);
        BusinessActivity::updateOrCreate(['name' => 'Retailer']);
        BusinessActivity::updateOrCreate(['name' => 'Accomodation', 'activity_category' => 'hotel']);
        BusinessActivity::updateOrCreate(['name' => 'Restaurant', 'activity_category' => 'hotel']);
        BusinessActivity::updateOrCreate(['name' => 'Bar', 'activity_category' => 'hotel']);
        BusinessActivity::updateOrCreate(['name' => 'Accomodation, Restaurant', 'activity_category' => 'hotel']);
        BusinessActivity::updateOrCreate(['name' => 'Restaurant, Bar', 'activity_category' => 'hotel']);
        BusinessActivity::updateOrCreate(['name' => 'Accomodation, Restaurant & Bar', 'activity_category' => 'hotel']);
    }
}
