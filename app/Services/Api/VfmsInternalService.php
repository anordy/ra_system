<?php

namespace App\Services\Api;

use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;

class VfmsInternalService
{
    use CustomAlert;

    /**
     * Get business units from VFMS by Z-number, this is used to verify z-number
     * @param [object] $business
     * @return [array] business units
     */
    public function getBusinessUnits($business, $location, $is_headquarter)
    {
        $vfmsWard = $is_headquarter ? $business->headquarter->ward->vfms_ward : $location->ward->vfms_ward;
        if (!$vfmsWard){
            return  [
                    'statusCode' => 400,
                    'statusMessage' => "Ward; '". $vfmsWard->ward->name."' selected for particular Business is not recognized yet from VFMS, contact Admin to complete this action."
                ];
        }
        $znumber_internal = config('modulesconfig.api_url') . '/vfms-internal/check_znumber';
        $access_token = (new ApiAuthenticationService)->getAccessToken();

        $data = [];

        if ($access_token) {
            $authorization = "Authorization: Bearer " . $access_token;
//            $payload = [
//                'locality_id' => $vfmsWard->locality_id,
//                'znumber' => $business->previous_zno
//            ];
            $payload = [
                'znumber' => $business->previous_zno,
            ];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $znumber_internal,
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
                    $authorization
                ),
            ));

            $response = curl_exec($curl);
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode != 200) {
                curl_close($curl);
                if (json_decode($response)->error_info == 200) {
                    return [
                        'message' => 'unsuccessful',
                        'data' => null
                    ];
                } else {
                    Log::error('ZNUMBER Something wrong: ' . $response);
                    return [
                        'message' => 'failed',
                        'data' => null
                    ];
                }
            }

            curl_close($curl);

            $res = json_decode($response, true);
            $data = $res;
        } else {
            Log::error('ZNUMBER: Error On Access token Authentication from Api Server!');
        }

        return $data;
    }
}
