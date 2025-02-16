<?php

namespace Database\Seeders;

use App\Models\MvrRegistrationType;
use App\Models\MvrRegistrationTypeCategory;
use Illuminate\Database\Seeder;

class MvrRegistrationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MvrRegistrationTypeCategoriesSeeder::class);

        $commercialId = MvrRegistrationTypeCategory::where('name', MvrRegistrationTypeCategory::COMMERCIAL)->first()->id;
        $gvtId = MvrRegistrationTypeCategory::where('name', MvrRegistrationTypeCategory::GOVERNMENT)->first()->id;
        $privateId = MvrRegistrationTypeCategory::where('name', MvrRegistrationTypeCategory::PRIVATE)->first()->id;
        $corporateId = MvrRegistrationTypeCategory::where('name', MvrRegistrationTypeCategory::CORPORATE)->first()->id;

        $data = [
            // Private
            ['mvr_registration_type_category_id' => $privateId, 'name' => MvrRegistrationType::TYPE_PRIVATE, 'plate_number_color' => 'Black and White', 'initial_plate_number' => 'Z111AA', 'external_defined' => 0],

            // Commercial
            ['mvr_registration_type_category_id' => $commercialId, 'name' => MvrRegistrationType::TYPE_COMMERCIAL_TAXI, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 0],
            ['mvr_registration_type_category_id' => $commercialId, 'name' => MvrRegistrationType::TYPE_COMMERCIAL_PRIVATE_HIRE, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 0],
            ['mvr_registration_type_category_id' => $commercialId, 'name' => MvrRegistrationType::TYPE_COMMERCIAL_GOODS_VEHICLE, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 0],
            ['mvr_registration_type_category_id' => $commercialId, 'name' => MvrRegistrationType::TYPE_COMMERCIAL_STAFF_BUS, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 0],
            ['mvr_registration_type_category_id' => $commercialId, 'name' => MvrRegistrationType::TYPE_COMMERCIAL_SCHOOL_BUS, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 0],
            ['mvr_registration_type_category_id' => $commercialId, 'name' => MvrRegistrationType::TYPE_COMMERCIAL_PUBLIC_BUS, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 0],
            ['mvr_registration_type_category_id' => $commercialId, 'name' => MvrRegistrationType::TYPE_COMMERCIAL_TOWN_BUS, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 0],
            ['mvr_registration_type_category_id' => $commercialId, 'name' => MvrRegistrationType::TYPE_COMMERCIAL_STAGE_BUS, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 0],
            ['mvr_registration_type_category_id' => $commercialId, 'name' => MvrRegistrationType::TYPE_COMMERCIAL_MOTORCYCLE, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 0],
            ['mvr_registration_type_category_id' => $commercialId, 'name' => MvrRegistrationType::TYPE_COMMERCIAL_TRICYCLE, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 0],

            // Government
            ['mvr_registration_type_category_id' => $gvtId, 'name' => MvrRegistrationType::TYPE_GOVERNMENT_SLS, 'plate_number_color' => 'Black and White', 'initial_plate_number' => 'SLS1111A', 'external_defined' => 0, 'plate_number_pattern' => 'SLS([0-9]{4})(_class_)',],
            ['mvr_registration_type_category_id' => $gvtId, 'name' => MvrRegistrationType::TYPE_GOVERNMENT_SMZ, 'plate_number_color' => 'Black and White', 'initial_plate_number' => 'SMZ1111A', 'external_defined' => 0, 'plate_number_pattern' => 'SMZ([0-9]{4})(_class_)',],
            ['mvr_registration_type_category_id' => $gvtId, 'name' => MvrRegistrationType::TYPE_GOVERNMENT_DIPLOMATIC, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 1],
            ['mvr_registration_type_category_id' => $gvtId, 'name' => MvrRegistrationType::TYPE_GOVERNMENT_MILITARY, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 1],
            ['mvr_registration_type_category_id' => $gvtId, 'name' => MvrRegistrationType::TYPE_GOVERNMENT_INTERNATIONAL, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 1],
            ['mvr_registration_type_category_id' => $gvtId, 'name' => MvrRegistrationType::TYPE_GOVERNMENT_DONOR_FUNDED, 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 1],

            // Corporate
            ['mvr_registration_type_category_id' => $corporateId, 'name' => MvrRegistrationType::TYPE_CORPORATE, 'plate_number_pattern' => 'SLS([0-9]{4})(_class_)', 'plate_number_color' => 'Black and White', 'initial_plate_number' => '', 'external_defined' => 0],
        ];

        foreach ($data as $row) {
            MvrRegistrationType::updateOrCreate([
                'mvr_registration_type_category_id' => $row['mvr_registration_type_category_id'],
                'name' => $row['name'],
            ], $row);
        }
    }
}
