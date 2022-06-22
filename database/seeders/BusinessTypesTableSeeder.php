<?php

namespace Database\Seeders;

use App\Models\BusinessType;
use Illuminate\Database\Seeder;

class BusinessTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BusinessType::create([
            'short_name' => 'sole-proprietor',
            'name' => 'Sole Proprietor'
        ]);

        BusinessType::create([
            'short_name' => 'partnership',
            'name' => 'Partnership'
        ]);

        BusinessType::create([
            'short_name' => 'company',
            'name' => 'Company'
        ]);
    }
}
