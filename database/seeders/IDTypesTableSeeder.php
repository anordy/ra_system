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
        IDType::create(['name' => 'NIDA']);
        IDType::create(['name' => 'Passport']);
    }
}
