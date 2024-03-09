<?php


namespace App\Http\Controllers\v1;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Services\Api\ApiAuthenticationService;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Log;

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

    /**
     * @throws \Exception
     */
    public function getPassportData($passportNumber, $permitNumber)
    {
        try {
            $immigration_endpoint = config('modulesconfig.api_url') . '/immigration/lookup';
            $access_token = (new ApiAuthenticationService)->getAccessToken();

            if ($access_token == null) {
                return ['data' => null, 'msg' => 'Authentication Failure.', 'code' => 401];
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

                if (curl_errno($curl)) {
                    $err = curl_error($curl);
                    curl_close($curl);
                    throw new \Exception($err);
                }

                curl_close($curl);
                return json_decode($response, true);
            }

        } catch (\Exception $exception) {
            Log::error('IMMIGRATION-CONTROLLER-GET-PASSPORT-DATA', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
            throw new \Exception($exception);
        }

    }
}
