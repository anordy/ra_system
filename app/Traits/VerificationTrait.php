<?php

namespace App\Traits;

use App\Enum\GeneralConstant;
use App\Events\SendMail;
use App\Jobs\RepostBillSignature;
use App\Jobs\RepostReturnSignature;
use App\Models\Returns\TaxReturn;
use App\Models\VerificationsLog;
use App\Models\ZmBill;
use App\Services\Verification\AuthenticationService;
use App\Services\Verification\PayloadInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait VerificationTrait
{

    public function verify(PayloadInterface $object): bool
    {
        if (config('app.env') == 'local') {
            return true;
        }
        
        $stringData = "";

        foreach ($object::getPayloadColumns() as $column) {
            $stringData .= $object->{$column};
        }

        Log::channel('verification')->info('Attempting to verify an instance.');

        try {
            $token = AuthenticationService::getAuthToken();

            $url = config('modulesconfig.verification.server_verify_url');

            $result = Http::withToken($token)
                ->withOptions(['verify' => false])
                ->post($url, [
                    'payload' => base64_encode($stringData),
                    'signature' => $object->ci_payload
                ]);

            Log::channel('verification')->info(json_decode($result, true)['verification'] ? GeneralConstant::COMPLETE : GeneralConstant::FAILED);

            $result = json_decode($result, true)['verification'] == 'true';

            if (!$result) {
                $object->update(['failed_verification' => true]);

                //  Save to failed verifications
                $verification = VerificationsLog::create([
                    'table' => $object->getTableName(),
                    'row_id' => $object->id,
                    'data' => json_encode($object)
                ]);

                if ($verification){
                    event(new SendMail('failed-verification', $verification));
                }

                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::channel('verification')->error($e);
            return false;
        }
    }

    public function sign(PayloadInterface $object): bool
    {
        if (config('app.env') == 'local') {
            return true;
        }

        $stringData = "";

        foreach ($object::getPayloadColumns() as $column) {
            $stringData .= $object->{$column};
        }

        Log::channel('verification')->info('Attempting to sign an instance.');

        try {
            // Get token
            $token = AuthenticationService::getAuthToken();

            // URL
            $url = config('modulesconfig.verification.server_sign_url');

            $result = Http::withToken($token)
                ->withOptions(['verify' => false])
                ->post($url, ['payload' => base64_encode($stringData)]);

            Log::channel('verification')->info(json_decode($result, true)['signature'] ? GeneralConstant::COMPLETE : GeneralConstant::FAILED);

            return $object->update(['ci_payload' => json_decode($result, true)['signature']]) == 1;
        } catch (\Exception $exception) {
            Log::channel('verification')->error($exception);

            if ($object instanceof TaxReturn) {
                dispatch(new RepostReturnSignature($object));
            } else if ($object instanceof ZmBill) {
                dispatch(new RepostBillSignature($object));
            }

            return 0;
        }
    }
}