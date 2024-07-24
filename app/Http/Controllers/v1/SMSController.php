<?php


namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\ZanMalipo\ZmCore;
use Exception;
use Illuminate\Http\Request;
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

    public function initSMS(Request $request)
    {
        $send_to = $request->input('send_to');
        $source = $request->input('source');
        $customer_message = $request->input('customer_message');

        return $this->sendSMS($send_to, $source, $customer_message);
    }

    public function sendSMS($send_to, $source, $customer_message)
    {
        $apiURL = config('modulesconfig.sms_url');

        if (!$customer_message || !$source || !$send_to) {
                throw new Exception('Missing customer message, source or recipient number');
        }

        $payload = [
            'username' => config('modulesconfig.sms_channel'),
            'password' => config('modulesconfig.sms_password'),
            'message' => $customer_message,
            'senderId' => $source,
            'phoneNumbers' => [ZmCore::formatPhone($send_to)],
        ];

        Log::error('SENDING-SMS');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
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
            Log::error('END-SENDING-SMS');
            curl_close($curl);
            throw new Exception('SMS sending failed');
        }
        curl_close($curl);
        Log::error('END-SENDING-SMS');
        return $response;
    }

}
