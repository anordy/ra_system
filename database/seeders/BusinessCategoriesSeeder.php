<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use Illuminate\Database\Seeder;

class BusinessCategoriesSeeder extends Seeder
{
    public function run()
    {
        BusinessCategory::updateOrCreate([
            'short_name' => 'sole-proprietor'
        ], [
            'short_name' => 'sole-proprietor',
            'name' => 'Sole Proprietor'
        ]);

        BusinessCategory::updateOrCreate([
            'short_name' => 'partnership',
        ],[
            'short_name' => 'partnership',
            'name' => 'Partnership'
        ]);

        BusinessCategory::updateOrCreate([
            'short_name' => 'company',
        ],[
            'short_name' => 'company',
            'name' => 'Company'
        ]);

        BusinessCategory::updateOrCreate([
            'short_name' => 'ngo',
        ],[
            'short_name' => 'ngo',
            'name' => 'NGO'
        ]);

        BusinessCategory::updateOrCreate([
            'short_name' => 'government-agency',
        ],[
            'short_name' => 'government-agency',
            'name' => 'Government Agencies'
        ]);
    }
}
