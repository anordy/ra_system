<?php


namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\Api\ApiAuthenticationService;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;

class ImmigrationController extends Controller
{
    use CustomAlert;
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
        $immigration_endpoint = config('modulesconfig.api_url') . '/immigration/lookup';
        $access_token = (new ApiAuthenticationService)->getAccessToken();

        if ($access_token == null) {
            return ['data' => null, 'msg' => 'Gateway Timed Out', 'code' => 504];
        } else {

            $authorization = "Authorization: Bearer " . $access_token;

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
            if (curl_errno($curl)) {
                $err = curl_error($curl);
                Log::error($err);
                return $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
            curl_close($curl);
            return json_decode($response, true);
        }
    }
}
