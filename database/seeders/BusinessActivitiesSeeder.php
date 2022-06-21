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
        BusinessActivity::create(['name' => 'Wholesale']);
        BusinessActivity::create(['name' => 'Retailer']);
    }
}
