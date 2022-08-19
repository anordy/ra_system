<?php

namespace App\Services\TRA;

use App\Models\Taxpayer;
use Faker\Factory;

class ServiceRequest
{

    public static function searchMotorVehicleByChassis($chassis){
        if (cache()->has($chassis)){
            return ['data'=>cache()->get($chassis),'status'=>'success'];
        }

        if (str_starts_with($chassis, 'N')){
            return ['data'=>[],'status'=>'failed','message'=>'Not Found!'];
        }

        $faker = Factory::create();
        $make = ['Toyota','Subaru','Nissan'][rand(0,2)];

        $owner = [
          'z_number'=> Taxpayer::query()->inRandomOrder()->first()->reference_no??'INVALID-Z-NUMBER',
          'name'=> $faker->name,
          'city'=> $faker->city,
          'tin'=> $faker->randomNumber(9),
          'address'=> $faker->streetAddress,
          'street'=> $faker->streetName,
          'shehia'=> $faker->streetName,
          'postal_address'=> $faker->address,
          'office_number'=> $faker->randomNumber(2),
          'email'=> $faker->email,
        ];
        $agent = [
            'z_number'=> Taxpayer::query()->first()->reference_no??'INVALID-Z-NUMBER',
            'name'=> $faker->name,
            'phone_number'=> '0719906669',
            'agent_id'=> 'AAAAA',
            'registration_no'=> 'RR5433Z',
        ];
        $data =  [
            'owner'=>$owner,
            'agent'=>$agent,
            'chassis_number' => $chassis,
            'engine_capacity' =>[1200,1300,2000,1750,3000,4000][$faker->numberBetween(0,5)],
            'engine_number'=>$chassis,
            'gross_weight'=>$faker->numberBetween(2000,6000),
            'number_of_axle'=>$faker->numberBetween(1,6),
            'year'=>$faker->numberBetween(1995,2020),
            'class'=>'A',
            'body_type'=>['Sedan','Saloon'][rand(0,1)],
            'make'=>$make,
            'model'=>['Toyota'=>['Alion','IST','Prado','RAV4','Probox'],'Subaru'=>['Forester','Legacy','Forester XT'],'Nissan'=>['XTrail','Tiida','Dualis']][$make][rand(0,2)],
            'imported_from'=>'Kenya',
            'fuel_type'=>['Petrol','Diesel'][rand(0,1)],
            'custom_number'=>$faker->randomAscii,
            'color'=>['White','Red','Blue','Silver'][rand(0,3)],
            'transmission_type'=>['Automatic','Manual','Other'][rand(0,2)],
            'seating_capacity'=>5,
            'usage'=>'Commercial'
        ];

        cache()->put($chassis,$data,100000);
        return ['data'=>$data,'status'=>'success'];
    }
}