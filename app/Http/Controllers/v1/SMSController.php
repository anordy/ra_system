<?php


namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $apiURL = config('modulesconfig.smstestapi');
        $Authorization = "YD5hFDSlN6rRVlyAoPCbmlU1YS4pLm9Xa7HbAQF55Nxdqmp90DFum05t9mJAvnLn";

        $data = [
            'send_to' => $send_to,
            'source' => $source,
            'customer_message' => $customer_message,
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
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "content-type: application/json",
                'Authorization:' . $Authorization,
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }


}
