<?php


namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ZanMalipo\ZmCore;
use Exception;
use Illuminate\Support\Facades\Log;

class SMSController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function initSMS(Request $request) {
        $send_to  = $request->input('send_to');
        $source = $request->input('source');
        $customer_message = $request->input('customer_message');

        return $this->sendSMS($send_to, $source, $customer_message);
    }

    public function sendSMS($send_to, $source, $customer_message) {
        $apiURL = config('modulesconfig.sms_url');
        
        $messages[] = [
            "text" => $customer_message,
                "msisdn" => ZmCore::formatPhone($send_to),
                "source" => $source
        ];
        
        $payload = [
            "channel" => [
                "channel" => config('modulesconfig.sms_channel'),
                "password" => config('modulesconfig.sms_password')
            ],
            "messages" => $messages
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiURL,
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
        if (curl_errno($curl)) {
            $err = curl_error($curl);
            Log::error($err);
            curl_close($curl);
            throw new Exception('SMS sending failed');
        }
        curl_close($curl);
        return $response;
    }
}
