<?php

namespace Database\Seeders;

use App\Models\MvrRegistrationType;
use App\Models\Permission;
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
        $data = [
           ['mvr_registration_type_category_id'=>1,'name'=>MvrRegistrationType::TYPE_PRIVATE_ORDINARY,'plate_number_pattern'=>'SMZ([0-9]{4})A','plate_number_color'=>'Black and White','initial_plate_number'=>'','external_defined'=>0],
           ['mvr_registration_type_category_id'=>1,'name'=>MvrRegistrationType::TYPE_PRIVATE_GOLDEN,'plate_number_color'=>'Black and White','initial_plate_number'=>'Z111AA','external_defined'=>0],
           ['mvr_registration_type_category_id'=>1,'name'=>MvrRegistrationType::TYPE_PRIVATE_PERSONALIZED,'plate_number_color'=>'Black and White','initial_plate_number'=>'','external_defined'=>0],
           ['mvr_registration_type_category_id'=>2,'name'=>MvrRegistrationType::TYPE_COMMERCIAL_TAXI,'plate_number_color'=>'Black and White','initial_plate_number'=>'','external_defined'=>0],
           ['mvr_registration_type_category_id'=>2,'name'=>MvrRegistrationType::TYPE_COMMERCIAL_PRIVATE_HIRE,'plate_number_color'=>'Black and White','initial_plate_number'=>'','external_defined'=>0],
           ['mvr_registration_type_category_id'=>2,'name'=>MvrRegistrationType::TYPE_COMMERCIAL_GOODS_VEHICLE,'plate_number_color'=>'Black and White','initial_plate_number'=>'','external_defined'=>0],
           ['mvr_registration_type_category_id'=>2,'name'=>MvrRegistrationType::TYPE_COMMERCIAL_STAFF_BUS,'plate_number_color'=>'Black and White','initial_plate_number'=>'','external_defined'=>0],
           ['mvr_registration_type_category_id'=>2,'name'=>MvrRegistrationType::TYPE_COMMERCIAL_SCHOOL_BUS,'plate_number_color'=>'Black and White','initial_plate_number'=>'','external_defined'=>0],
           ['mvr_registration_type_category_id'=>3,'name'=>MvrRegistrationType::TYPE_GOVERNMENT,'plate_number_color'=>'Black and White','initial_plate_number'=>'','external_defined'=>0],
           ['mvr_registration_type_category_id'=>4,'name'=>MvrRegistrationType::TYPE_CORPORATE,'plate_number_pattern'=>'SLS([0-9]{4})(_class_)','plate_number_color'=>'Black and White','initial_plate_number'=>'','external_defined'=>0],
           ['mvr_registration_type_category_id'=>5,'name'=>MvrRegistrationType::TYPE_DONOR_FUNDED,'plate_number_color'=>'Black and White','initial_plate_number'=>'','external_defined'=>1],
           ['mvr_registration_type_category_id'=>6,'name'=>MvrRegistrationType::TYPE_DIPLOMATIC,'plate_number_color'=>'Black and White','initial_plate_number'=>'','external_defined'=>1],
           ['mvr_registration_type_category_id'=>7,'name'=>MvrRegistrationType::TYPE_MILITARY,'plate_number_color'=>'Black and White','initial_plate_number'=>'','external_defined'=>1],
        ];
        foreach ($data as $row) {
            MvrRegistrationType::updateOrCreate($row);
        }
    }
}
