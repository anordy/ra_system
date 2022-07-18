<?php

namespace Database\Seeders;

use App\Models\IDType;
use Illuminate\Database\Seeder;

class IDTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IDType::updateOrCreate(['name' => 'NIDA']);
        IDType::updateOrCreate(['name' => 'PASSPORT']);
    }
}
