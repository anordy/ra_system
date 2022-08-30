<?php

namespace Database\Seeders;

use App\Models\Assesment;
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
        MvrRegistrationTypeCategory::query()->updateOrcreate(['id'=>1, 'name'=>'Private']);
        MvrRegistrationTypeCategory::query()->updateOrcreate(['id'=>2, 'name'=>'Commercial']);
        MvrRegistrationTypeCategory::query()->updateOrcreate(['id'=>3, 'name'=>'Government']);
        MvrRegistrationTypeCategory::query()->updateOrcreate(['id'=>4, 'name'=>'Corporate']);
        MvrRegistrationTypeCategory::query()->updateOrcreate(['id'=>5, 'name'=>'Donor Funded Project']);
        MvrRegistrationTypeCategory::query()->updateOrcreate(['id'=>6, 'name'=>'Diplomat']);
        MvrRegistrationTypeCategory::query()->updateOrcreate(['id'=>7, 'name'=>'Military']);

    }
}
