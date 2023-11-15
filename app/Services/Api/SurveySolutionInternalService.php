<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Log;

class SurveySolutionInternalService
{

    public function getPropertyInformation(string $identifierType, string $identifierNumber) {

        $accessToken = (new ApiAuthenticationService)->getAccessToken();

        if ($accessToken) {

            $authorization = 'Bearer ' .$accessToken;

            $tinUrl = config('modulesconfig.api_url') . '/tra/tin/post-znumber';

            $payload = [
                'indentifierType' => $identifierType,
                'identifierNumber' => $identifierNumber
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $tinUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "content-type: application/json",
                    "authorization: $authorization"
                ),
            ));

            $response = curl_exec($curl);

            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($statusCode != 200) {
                curl_close($curl);
                if (json_decode($response) == 200){
                    return [
                        'message' => 'unsuccessful',
                        'data' => null
                    ];
                } else {
                    Log::error('FAILED TO POST Z-NUMBER: '.$response);
                    return [
                        'message' => 'failed',
                        'data' => null
                    ];
                }
            } else {
                return json_decode($response, true);
            }

        } else {
            Log::error('FAILED TO AUTHENTICATE');
            return [
                'message' => 'failed',
                'data' => null
            ];
        }
    }

}