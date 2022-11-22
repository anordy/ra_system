<?php

namespace App\Services\Api;

use App\Models\Returns\TaxReturn;
use Illuminate\Support\Facades\Log;
use App\Models\Returns\ReturnStatus;
use App\Models\TaxAssessments\TaxAssessment;
use App\Services\Api\ApiAuthenticationService;

class ZanMalipoInternalService
{
    /**
     * Create Bill 
     */
    public function createBill($bill)
    {
        $zanmalipo_internal = config('modulesconfig.api_url') . '/zanmalipo-internal/createBill';

        $access_token = (new ApiAuthenticationService)->getAccessToken();
        $authorization = "Authorization: Bearer ". $access_token;

        $payload = [
            'bill_id' => $bill->id,
            'generated_by' => 'ZRB',
            'approved_by' => 'ZRB'
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $zanmalipo_internal,
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
        }
        curl_close($curl);
        $res = json_decode($response, true);
        $billable = $bill->billable;

        if ($res['data']['status_code'] === 7101) {
            if ($bill->billable_type == TaxAssessment::class || $bill->billable_type == TaxReturn::class) {
                $billable->payment_status = ReturnStatus::CN_GENERATING;
            } else {
                $billable->status = ReturnStatus::CN_GENERATING;
            }
        } else {
            if ($bill->billable_type == TaxAssessment::class || $bill->billable_type == TaxReturn::class) {
                $billable->payment_status = ReturnStatus::CN_GENERATION_FAILED;
            } else {
                $billable->status = ReturnStatus::CN_GENERATION_FAILED;
            }
        }
        $billable->save();
        return $res;
    }

    /**
     * Cancel Bill 
     */
    public function cancelBill($bill, $cancellationReason)
    {
        $zanmalipo_internal = config('modulesconfig.api_url') . '/zanmalipo-internal/cancelBill';

        $access_token = (new ApiAuthenticationService)->getAccessToken();
        $authorization = "Authorization: Bearer ". $access_token;

        $payload = [
            'bill_id' => $bill->id,
            'reason' => $cancellationReason
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $zanmalipo_internal,
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
        }
        curl_close($curl);
        return json_decode($response, true);
    }


    /**
     * Update Bill 
     */
    public function updateBill($bill, $expireDate)
    {
        $zanmalipo_internal = config('modulesconfig.api_url') . '/zanmalipo-internal/updateBill';

        $access_token = (new ApiAuthenticationService)->getAccessToken();
        $authorization = "Authorization: Bearer ". $access_token;

        $payload = [
            'bill_id' => $bill->id,
            'expire_date' => $expireDate
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $zanmalipo_internal,
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
        }
        curl_close($curl);
        return json_decode($response, true);
    }

    /**
     * Send Reconciliation 
     */
    public function sendRecon()
    {
        $zanmalipo_internal = config('modulesconfig.api_url') . '/zanmalipo-internal/sendRecon';

        $access_token = (new ApiAuthenticationService)->getAccessToken();
        $authorization = "Authorization: Bearer ". $access_token;

        $payload = [
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $zanmalipo_internal,
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
        }
        curl_close($curl);
        return json_decode($response, true);
    }
}