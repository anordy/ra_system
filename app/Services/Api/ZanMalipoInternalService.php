<?php

namespace App\Services\Api;

use App\Models\BillingStatus;
use App\Models\DlLicenseApplication;
use App\Models\MvrDeregistration;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrRegistration;
use App\Models\MvrRegistrationParticularChange;
use App\Models\MvrRegistrationStatusChange;
use App\Models\PropertyTax\PropertyPayment;
use App\Models\PublicService\PublicServiceReturn;
use App\Models\RenewTaxAgentRequest;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAgent;
use App\Models\TaxAgentStatus;
use App\Models\TaxAssessments\TaxAssessment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ZanMalipoInternalService
{
    

    /**
     * Create Bill
     */
    public function createBill($bill)
    {
        $zanmalipo_internal = config('modulesconfig.api_url') . '/zanmalipo-internal/createBill';

        $access_token = (new ApiAuthenticationService)->getAccessToken();

        if ($access_token == null) {
            $billable = $bill->billable;
            if (
                $bill->billable_type == TaxAssessment::class
                || $bill->billable_type == TaxReturn::class
                || $bill->billable_type == PropertyPayment::class
                || $bill->billable_type == MvrOwnershipTransfer::class
                || $bill->billable_type == MvrDeregistration::class
                || $bill->billable_type == MvrRegistration::class
                || $bill->billable_type == MvrRegistrationStatusChange::class
                || $bill->billable_type == DlLicenseApplication::class
                || $bill->billable_type == PublicServiceReturn::class
                || $bill->billable_type == MvrRegistrationParticularChange::class
            ) {
                $billable->payment_status = ReturnStatus::CN_GENERATION_FAILED;
                if ($bill->billable_type == TaxReturn::class && $billable->return) {
                    $billable->return->status = ReturnStatus::CN_GENERATION_FAILED;
                    $billable->return->save();
                }
            } else if ($bill->billable_type == TaxAgent::class || $bill->billable_type == RenewTaxAgentRequest::class) {
                $billable->billing_status = BillingStatus::CN_GENERATION_FAILED;
                $billable->status = TaxAgentStatus::VERIFIED;
            } else {
                $billable->status = ReturnStatus::CN_GENERATION_FAILED;
            }
            $billable->save();
            $bill->zan_trx_sts_code = 0;
            $bill->zan_status = 'failed';
            $bill->status = 'failed';
            $bill->save();
            $this->sign($bill);
            return null;
        } else {
            $authorization = "Authorization: Bearer " . $access_token;

            $payload = [
                'bill_id' => $bill->id,
                'generated_by' => 'ZRA',
                'approved_by' => 'ZRA'
            ];

            Log::info('-------SENDING BILL GENERATION REQUEST--------');
            Log::info('PAYLOAD', [$payload]);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $zanmalipo_internal,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_CONNECTTIMEOUT => 30,
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

            Log::info('RESPONSE', [$res]);
            Log::info('-------END CONTROL NUMBER GENERATION REQUEST--------');

            if ($res['data']['status_code'] === 7101) {
                if (
                    $bill->billable_type == TaxAssessment::class
                    || $bill->billable_type == TaxReturn::class
                    || $bill->billable_type == PropertyPayment::class
                    || $bill->billable_type == MvrOwnershipTransfer::class
                    || $bill->billable_type == MvrDeregistration::class
                    || $bill->billable_type == MvrRegistration::class
                    || $bill->billable_type == MvrRegistrationStatusChange::class
                    || $bill->billable_type == DlLicenseApplication::class
                    || $bill->billable_type == PublicServiceReturn::class
                    || $bill->billable_type == MvrRegistrationParticularChange::class
                ) {
                    $billable->payment_status = ReturnStatus::CN_GENERATING;
                } else if ($bill->billable_type == TaxAgent::class || $bill->billable_type == RenewTaxAgentRequest::class) {
                    $billable->status = TaxAgentStatus::VERIFIED;
                    $billable->billing_status = BillingStatus::CN_GENERATED;
                } else {
                    $billable->status = ReturnStatus::CN_GENERATING;
                }
            } else {
                if (
                    $bill->billable_type == TaxAssessment::class
                    || $bill->billable_type == TaxReturn::class
                    || $bill->billable_type == PropertyPayment::class
                    || $bill->billable_type == MvrOwnershipTransfer::class
                    || $bill->billable_type == MvrDeregistration::class
                    || $bill->billable_type == MvrRegistration::class
                    || $bill->billable_type == MvrRegistrationStatusChange::class
                    || $bill->billable_type == DlLicenseApplication::class
                    || $bill->billable_type == PublicServiceReturn::class
                    || $bill->billable_type == MvrRegistrationParticularChange::class
                ) {
                    $billable->payment_status = ReturnStatus::CN_GENERATION_FAILED;
                } else if ($bill->billable_type == TaxAgent::class || $bill->billable_type == RenewTaxAgentRequest::class) {
                    $billable->billing_status = BillingStatus::CN_GENERATION_FAILED;
                    $billable->status = TaxAgentStatus::VERIFIED;
                } else {
                    $billable->status = ReturnStatus::CN_GENERATION_FAILED;
                }
            }
            $billable->save();
            $this->sign($bill);
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
        $authorization = "Authorization: Bearer " . $access_token;

        $payload = [
            'bill_id' => $bill->id,
            'reason' => $cancellationReason,
            'staff_id' => Auth::id() ?? 0
        ];

        Log::info('-------SENDING BILL CANCELLATION  REQUEST--------');
        Log::info('PAYLOAD', [$payload]);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $zanmalipo_internal,
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
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($statusCode != 200) {
            curl_close($curl);
            throw new \Exception($response);
        }

        Log::info('RESPONSE', [$response]);
        Log::info('-------END CONTROL NUMBER GENERATION REQUEST--------');

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
        $authorization = "Authorization: Bearer " . $access_token;

        $payload = [
            'bill_id' => $bill->id,
            'expire_date' => $expireDate,
            'staff_id' => Auth::id() ?? 0
        ];

        Log::info('-------SENDING BILL UPDATING  REQUEST--------');
        Log::info('PAYLOAD', [$payload]);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $zanmalipo_internal,
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
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($statusCode != 200) {
            curl_close($curl);
            throw new \Exception($response);
        }

        Log::info('RESPONSE', [$response]);
        Log::info('-------END CONTROL NUMBER GENERATION REQUEST--------');

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
        $authorization = "Authorization: Bearer " . $access_token;

        $payload = [
            'recon_id' => $recon_id
        ];

        Log::info('-------SENDING RECONCILIATION  REQUEST--------');
        Log::info('PAYLOAD', [$payload]);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $zanmalipo_internal,
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
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($statusCode != 200 || !$response) {
            curl_close($curl);
            throw new \Exception($response);
        }

        Log::info('RESPONSE', [$response]);
        Log::info('-------END CONTROL NUMBER GENERATION REQUEST--------');

        curl_close($curl);
        return json_decode($response, true);
    }
}