<?php

namespace App\Services\Api;

class ApiAuthenticationService
{

    public function getAccessToken()
    {
        $loginApiUrl = config('modulesconfig.api_url') . '/login';

        $payload = [
            'username' => config('modulesconfig.api_server_username'),
            'password' => config('modulesconfig.api_server_password'),
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $loginApiUrl,
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
            ),
        ));

        $response = curl_exec($curl);
        $error_msg = curl_error($curl);
        curl_close($curl);
        return json_decode($response, true)['data']['access_token'];
    }

}