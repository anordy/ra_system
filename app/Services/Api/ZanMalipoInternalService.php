<?php

namespace App\Services\Api;

use App\Models\TaxAgent;
use App\Models\BillingStatus;
use App\Models\TaxAgentStatus;
use App\Models\Returns\TaxReturn;
use Illuminate\Support\Facades\Log;
use App\Models\RenewTaxAgentRequest;
use App\Models\Returns\ReturnStatus;
use Illuminate\Support\Facades\Auth;
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

        if ($access_token == 0) {
            $billable = $bill->billable;
            if ($bill->billable_type == TaxAssessment::class || $bill->billable_type == TaxReturn::class) {
                $billable->payment_status = ReturnStatus::CN_GENERATION_FAILED;
                if ($bill->billable_type == TaxReturn::class && $billable->return) {
                    $billable->return->status = ReturnStatus::CN_GENERATION_FAILED;
                    $billable->return->save();
                }
            } else if ($bill->billable_type == TaxAgent::class || $bill->billable_type == RenewTaxAgentRequest::class) {
                $billable->billing_status = BillingStatus::CN_GENERATION_FAILED;
            } else {
                $billable->status = ReturnStatus::CN_GENERATION_FAILED;
            }
            $billable->save();
            $bill->zan_trx_sts_code = 0;
            $bill->zan_status = 'failed';
            $bill->status = 'failed';
            $bill->save();
        } else {
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
            CURLOPT_CONNECTTIMEOUT => 30,
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
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($statusCode != 200) {
            Log::error(curl_error($curl));
            curl_close($curl);
            throw new \Exception($response);
        }
        curl_close($curl);
        $res = json_decode($response, true);
        $billable = $bill->billable;

        if ($res['data']['status_code'] === 7101) {
            if ($bill->billable_type == TaxAssessment::class || $bill->billable_type == TaxReturn::class) {
                $billable->payment_status = ReturnStatus::CN_GENERATING;
            } else if ($bill->billable_type == TaxAgent::class) {
                $billable->status = TaxAgentStatus::VERIFIED;
                $billable->billing_status = BillingStatus::CN_GENERATED;
                $billable->verifier_id = Auth::id();
                $billable->verifier_true_comment = ''; // Not necessary to have comments on approval
                $billable->verified_at = now();
            } else  {
                $billable->statusCode = ReturnStatus::CN_GENERATING;
            }
        } else {
            if ($bill->billable_type == TaxAssessment::class || $bill->billable_type == TaxReturn::class) {
                $billable->payment_status = ReturnStatus::CN_GENERATION_FAILED;
            } else if ($bill->billable_type == TaxAgent::class || $bill->billable_type == RenewTaxAgentRequest::class) {
                $billable->billing_status = BillingStatus::CN_GENERATION_FAILED;
                $billable->status = TaxAgentStatus::PENDING;
            } else {
                $billable->statusCode = ReturnStatus::CN_GENERATION_FAILED;
            }
        }
        $billable->save();
        return $res;
    }
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
            'reason' => $cancellationReason,
            'staff_id' => Auth::id()
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
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($statusCode != 200) {
            curl_close($curl);
            throw new \Exception($response);
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
            'expire_date' => $expireDate,
            'staff_id' => Auth::id()
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
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($statusCode != 200) {
            curl_close($curl);
            throw new \Exception($response);
        }

        curl_close($curl);
        return json_decode($response, true);
    }

    /**
     * Request Reconciliation 
     */
    public function requestRecon($recon_id)
    {
        $zanmalipo_internal = config('modulesconfig.api_url') . '/zanmalipo-internal/sendRecon';

        $access_token = (new ApiAuthenticationService)->getAccessToken();
        $authorization = "Authorization: Bearer ". $access_token;

        $payload = [
            'recon_id' => $recon_id
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
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($statusCode != 200) {
            curl_close($curl);
            throw new \Exception($response);
        }
        curl_close($curl);
        return json_decode($response, true);
    }
}