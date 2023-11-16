<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Log;

class SurveySolutionInternalService
{

    public function getPropertyInformation(string $identifierType, string $identifierNumber) {

        $accessToken = (new ApiAuthenticationService)->getAccessToken();

        if ($accessToken) {

            $authorization = 'Bearer ' .$accessToken;

            $url = config('modulesconfig.api_url') . '/property-tax/property-info';

            $payload = [
                $identifierType => $identifierNumber,
            ];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "content-type: application/json",
                    "authorization: $authorization"
                ),
            ));

            $response = curl_exec($curl);

            Log::info($response);

            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($statusCode != 200) {
                curl_close($curl);
                Log::error('FAILED');
                return [
                    'message' => 'failed',
                    'data' => null
                ];
            } else {
                curl_close($curl);
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