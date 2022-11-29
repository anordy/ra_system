<?php

namespace App\Services\Api;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class BpraInternalService
{
    use LivewireAlert;

    public function getData($business){

        $bpra_internal = config('modulesconfig.api_url') . '/get_entity_full';
        $access_token = (new ApiAuthenticationService)->getAccessToken();

        if ($access_token) {
            $authorization = "Authorization: Bearer ". $access_token;

            $payload = [
                'registration_no' => $business->reg_no
            ];
    
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $bpra_internal,
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
                throw new \Exception($response);
                return $this->alert('error', 'Something went wrong');
            }
            curl_close($curl);
            $res = json_decode($response, true);

            $business->authorities_verified_at = Carbon::now();
            $business->save();

            if ($res) {
                return [
                    'reg_number' => $res['data']['reg_number'],
                    'business_name' => $res['data']['entity_name'],
                    'reg_date' => $res['data']['reg_date'],
                    'mob_phone' => $res['data']['mob_phone'],
                    'email' => $res['data']['email'],
                ];
            } else {
                return [
                    'reg_number' => '',
                    'business_name' => '',
                    'reg_date' => '',
                    'mob_phone' => '',
                    'email' => '',
                ];
            }
            
        } else {
            Log::error('Error On Access token Authentication from Api Server!');
            return [];
        }
    }
}