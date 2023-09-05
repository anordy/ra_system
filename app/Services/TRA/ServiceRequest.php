<?php

namespace App\Services\TRA;

use App\Models\BusinessLocation;
use App\Models\MvrAgent;
use App\Models\Taxpayer;
use App\Services\Api\ApiAuthenticationService;
use Faker\Factory;
use Illuminate\Support\Facades\Log;

class ServiceRequest
{

    public static function searchMotorVehicleByChassis($chassis){

        $data = (new ServiceRequest)->getChassisNumber($chassis);

        if (cache()->has($chassis)){
            return ['data'=>cache()->get($chassis),'status'=>'success'];
        }

        if (str_starts_with($chassis, 'N')){
            return ['data'=>[],'status'=>'failed','message'=>'Not Found!'];
        }

        $faker = Factory::create();
        $make = ['Toyota','Subaru','Nissan'][rand(0,2)];

        // Fetch owner/importer details from TIN endpoint

//        $data =  [
//            'chassis_number' => $chassis,
//            'engine_capacity' =>[1200,1300,2000,1750,3000,4000][$faker->numberBetween(0,5)],
//            'engine_number'=>$chassis,
//            'gross_weight'=>$faker->numberBetween(2000,6000),
//            'number_of_axle'=>$faker->numberBetween(1,6),
//            'year'=>$faker->numberBetween(1995,2020),
//            'class'=>'DA',
//            'body_type'=>['Sedan','Saloon'][rand(0,1)],
//            'make'=>$make,
//            'model'=>['Toyota'=>['Alion','IST','Prado','RAV4','Probox'],'Subaru'=>['Forester','Legacy','Forester XT'],'Nissan'=>['XTrail','Tiida','Dualis']][$make][rand(0,2)],
//            'imported_from'=>'Kenya',
//            'fuel_type'=>['Petrol','Diesel'][rand(0,1)],
//            'custom_number'=>$faker->randomAscii,
//            'color'=>['White','Red','Blue','Silver'][rand(0,3)],
//            'transmission_type'=>['Automatic','Manual','Other'][rand(0,2)],
//            'seating_capacity'=>5,
//            'usage'=>'Commercial'
//        ];

        return ['data'=>$data, 'status'=>'success'];
    }

    public function getChassisNumber(string $chassisNumber)
    {
        $apiUrl = config('modulesconfig.api_url') . '/tra/mvr?chassisNumber='.$chassisNumber;

        $accessToken = (new ApiAuthenticationService)->getAccessToken();

        if ($accessToken) {

            $authorization = "Authorization: Bearer ". $accessToken;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $apiUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "content-type: application/json",
                    $authorization
                ),
            ));

            $response = curl_exec($curl);

            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($statusCode != 200) {
                // Handle gateway timeout, request timeout by forwading to next api call to handle error ie. zan malipo
                if ($statusCode == 0 || $statusCode == 408 || curl_errno($curl) == 28) {
                    return null;
                }

                Log::error(curl_error($curl));
                curl_close($curl);
            }
            curl_close($curl);
            return json_decode($response, true)['data'];
        } else {
            Log::error('Failed to get Access Token');
        }


    }
}