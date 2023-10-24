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

        $response = (new ServiceRequest)->getRegisteredChassisNumber($chassis);

        if ($response && $response['data']) {
            $data = $response['data'];
            return ['data'=> $data, 'status'=>'success'];
        } else if ($response && $response['data'] == null) {
            return ['data'=> null, 'status'=>'failed', 'message' => $response['message']];
        } else {
            return ['data'=> null, 'status'=>'failed'];
        }

    }

    public function getRegisteredChassisNumber(string $chassisNumber)
    {
        $apiUrl = config('modulesconfig.api_url') . '/tra/mvr/registered/'.$chassisNumber;

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
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "content-type: application/json",
                    $authorization
                ),
            ));

            $response = curl_exec($curl);

            $response = json_decode($response, TRUE);

            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($statusCode != 200) {
                curl_close($curl);
                if ($response){
                    return [
                        'message' => $response['data']['msg'],
                        'data' => null
                    ];
                } else {
                    Log::error('TRA MVR Something wrong: '.$response);
                    return [
                        'message' => 'failed',
                        'data' => null
                    ];
                }

            } else {
                return $response;
            }
        } else {
            Log::error('Failed to get Access Token');
        }

    }

}