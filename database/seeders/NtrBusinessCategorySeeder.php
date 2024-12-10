<?php

namespace Database\Seeders;

use App\Models\Ntr\NtrBusinessCategory;
use Illuminate\Database\Seeder;

class NtrBusinessCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Corporation', 'Partnership', 'Association', 'Other'];

        foreach ($categories as $category) {
            NtrBusinessCategory::create(['name' => $category]);
        }
    }
}
