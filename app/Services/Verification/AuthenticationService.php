<?php

namespace App\Services\Verification;

use Illuminate\Support\Facades\Log;

class AuthenticationService {
    public static function getAuthToken(){
        $url = config('modulesconfig.verification.server_auth_url');

        $payload = [
            'grant_type' => 'password',
            'username' => config('modulesconfig.verification.server_username'),
            'password' => config('modulesconfig.verification.server_password'),
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($payload),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                "Authorization: Basic ". config('modulesconfig.verification.server_token'),
            ),
        ));

        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($statusCode != 200) {
            Log::error(curl_error($curl));
            curl_close($curl);
            throw new \Exception($response);
        }
        curl_close($curl);
        return json_decode($response, true)['access_token'];
    }
}