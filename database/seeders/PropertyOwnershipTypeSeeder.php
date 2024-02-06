<?php

namespace Database\Seeders;

use App\Enum\PropertyOwnershipTypeStatus;
use App\Models\PropertyTax\PropertyOwnershipType;
use Illuminate\Database\Seeder;

class PropertyOwnershipTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PropertyOwnershipType::updateOrCreate(['name' => PropertyOwnershipTypeStatus::PRIVATE]);
        PropertyOwnershipType::updateOrCreate(['name' => PropertyOwnershipTypeStatus::GOVERNMENT]);
        PropertyOwnershipType::updateOrCreate(['name' => PropertyOwnershipTypeStatus::RELIGIOUS]);
    }
}
