<?php


namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\ApiAuthenticationService;

class ZanIDController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getZanIDData($zanIDNumber)
    {
        $zanid_endpoint = config('modulesconfig.api_url') . '/zanid-internal/lookup';
        $access_token = (new ApiAuthenticationService)->getAccessToken();
        $authorization = "Authorization: Bearer ". $access_token;
        
        $payload = [
            'zanid' => $zanIDNumber,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $zanid_endpoint, 
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_ENCODING => '', 
            CURLOPT_MAXREDIRS => 10, 
            CURLOPT_TIMEOUT => 0, 
            CURLOPT_FOLLOWLOCATION => true, 
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, 
            CURLOPT_CUSTOMREQUEST => 'POST',
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
        }
        curl_close($curl);
        return json_decode($response, true)['data'];
    }
}
