<?php

namespace Database\Seeders;

use App\Models\MvrRegistrationType;
use App\Models\MvrRegistrationTypeCategory;
use Illuminate\Database\Seeder;

class MvrRegistrationTypeCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MvrRegistrationTypeCategory::query()->updateOrcreate(['id' => 1, 'name' => MvrRegistrationTypeCategory::PRIVATE]);
        MvrRegistrationTypeCategory::query()->updateOrcreate(['id' => 2, 'name' => MvrRegistrationTypeCategory::COMMERCIAL]);
        MvrRegistrationTypeCategory::query()->updateOrcreate(['id' => 4, 'name' => MvrRegistrationTypeCategory::CORPORATE]);
        MvrRegistrationTypeCategory::query()->updateOrcreate(['id' => 3, 'name' => MvrRegistrationTypeCategory::GOVERNMENT]);
    }
}
