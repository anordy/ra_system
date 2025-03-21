<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Log;

class TraInternalService
{

    public function getTinNumber(string $tinNumber) {

        $accessToken = (new ApiAuthenticationService)->getAccessToken();

        if ($accessToken) {

            $authorization = 'Bearer ' .$accessToken;

            $tinUrl = config('modulesconfig.api_url') . '/tra/tin/'.$tinNumber;

            Log::info('------REQUESTING TIN------', [$tinUrl]);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $tinUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "content-type: application/json",
                    $authorization
                ),
            ));

            $response = curl_exec($curl);

            Log::info($response);

            $response = json_decode($response, TRUE);

            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($statusCode != 200) {
                curl_close($curl);
                if ($response){
                    return [
                        'message' => $response['data']['msg'] ?? 'Failed to retrieve TIN Number',
                        'data' => null
                    ];
                } else {
                    Log::error('TRA TIN Something wrong: '.$response);
                    return [
                        'message' => 'Failed to get TIN Number',
                        'data' => null
                    ];
                }

            } else {
                return $response;
            }

        } else {
            Log::error('FAILED TO AUTHENTICATE');
            return [
                'message' => 'Failed to Authenticate',
                'data' => null
            ];
        }
    }

    public function postZNumber($businessId) {

        $accessToken = (new ApiAuthenticationService)->getAccessToken();

        if ($accessToken) {

            $authorization = 'Bearer ' .$accessToken;

            $tinUrl = config('modulesconfig.api_url') . '/tra/tin/post-znumber';

            $payload = [
                'businessId' => $businessId,
            ];

            Log::info('------POSTING Z-NUMBER', [json_encode($payload)]);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $tinUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "content-type: application/json",
                    $authorization
                ),
            ));

            $response = curl_exec($curl);

            Log::info($response);

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

    public function postPlateNumber(string $chassisNumber, string $plateNumber, string  $registrationType) {

        $accessToken = (new ApiAuthenticationService)->getAccessToken();

        if ($accessToken) {

            $authorization = 'Bearer ' .$accessToken;

            $tinUrl = config('modulesconfig.api_url') . '/tra/mvr/post-plate-number';

            $payload = [
                'chassisNumber' => $chassisNumber,
                'plateNumber' => $plateNumber,
                'registrationType' => $registrationType
            ];

            Log::info('------POSTING PLATE NUMBER', [json_encode($payload)]);


            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $tinUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    "accept: application/json",
                    "content-type: application/json",
                    $authorization
                ),
            ));

            $response = curl_exec($curl);

            Log::info($response);

            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if ($statusCode != 200) {
                curl_close($curl);
                if (json_decode($response) == 200){
                    return [
                        'message' => 'unsuccessful',
                        'data' => null
                    ];
                } else {
                    Log::error('FAILED TO POST PLATE NUMBER: '.$response);
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