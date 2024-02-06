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
        IDType::updateOrCreate(['name' => IDType::NIDA]);
        IDType::updateOrCreate(['name' => IDType::ZANID]);
        IDType::updateOrCreate(['name' => IDType::PASSPORT]);
        IDType::updateOrCreate(['name' => IDType::NIDA_ZANID]);
        IDType::updateOrCreate(['name' => IDType::TIN]);
    }
}
