<?php


namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;

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
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://anpr.rahisi.co.tz/vitambulisho.php', 
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_ENCODING => '', 
            CURLOPT_MAXREDIRS => 10, 
            CURLOPT_TIMEOUT => 0, 
            CURLOPT_FOLLOWLOCATION => true, 
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, 
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "card=040052740", 
            CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded', 'Cookie: PHPSESSID=65e48ae3bf30c636f582954a1e0754f7'),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
}
