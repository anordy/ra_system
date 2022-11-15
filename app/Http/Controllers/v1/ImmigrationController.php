<?php


namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\ApiAuthenticationService;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ImmigrationController extends Controller
{
    use LivewireAlert;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function getPassportData($passportNumber, $permitNumber)
    {
        if(config('app.env') == 'local'){
            $response   = json_decode(file_get_contents(public_path() . '/api/Immigration.json'), true)['data'][0];
            return $response;
        }

        $immigration_endpoint = config('modulesconfig.immigration_test_api') . '/immigration/lookupTest';

        $access_token = (new ApiAuthenticationService)->getAccessToken();

        $authorization = "Authorization: Bearer ". $access_token;

        $payload = [
            'passportNumber' => $passportNumber,
            'permitNumber' => $permitNumber,
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $immigration_endpoint,
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
        $err = curl_error($curl);
        if(curl_errno($curl)){
            $err = curl_error($curl);
            Log::error($err);
            return $this->alert('error', 'Something went wrong');
        }
        curl_close($curl);
        return json_decode($response, true)['data'][0];
    }

}
