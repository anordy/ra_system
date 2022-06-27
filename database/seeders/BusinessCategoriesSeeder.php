<?php

namespace Database\Seeders;

use App\Models\BusinessCategory;
use Illuminate\Database\Seeder;

class BusinessCategoriesSeeder extends Seeder
{
    public function run()
    {
        BusinessCategory::create([
            'short_name' => 'sole-proprietor',
            'name' => 'Sole Proprietor'
        ]);

        BusinessCategory::create([
            'short_name' => 'partnership',
            'name' => 'Partnership'
        ]);

        BusinessCategory::create([
            'short_name' => 'company',
            'name' => 'Company'
        ]);

        BusinessCategory::create([
            'short_name' => 'ngo',
            'name' => 'NGO'
        ]);
    }
}
