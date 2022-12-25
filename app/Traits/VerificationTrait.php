<?php

namespace App\Traits;

use App\Services\Verification\AuthenticationService;
use App\Services\Verification\PayloadInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait VerificationTrait{

    public function verify(PayloadInterface $object): bool
    {
        Log::channel('verification')->info('Attempting to verify an instance.', ['instance' => $object]);

        $stringData = "";

        foreach ($object::getPayloadColumns()as $column){
            $stringData .= $object->{$column};
        }

        return $this->verifySignature($stringData, $object->ci_payload);
    }

    private function verifySignature($data, $ci_payload): bool
    {
        // TODO: Maybe try n' catch

        // Get token
        $token = AuthenticationService::getAuthToken();

        // URL
        $url = config('modulesconfig.verification.server_url') . '/PrepaidCardServices/v1/Crypto/verify';

        $result = Http::withToken($token)
            ->withOptions(['verify' => false])
            ->post($url, [
                'payload' => base64_encode($data),
                'signature' => $ci_payload
            ]);

        return json_decode($result, true)['verification'] == 'true';
    }

    public function sign(PayloadInterface $object): bool
    {
        Log::channel('verification')->info('Attempting to sign an instance.', ['instance' => $object]);

        $stringData = "";

        foreach ($object::getPayloadColumns() as $column){
            $stringData .= $object->{$column};
        }

        return $object->update([
                'ci_payload' => $this->getSignature($stringData)
            ]) == 1;
    }

    private function getSignature($data){
        // Get token
        $token = AuthenticationService::getAuthToken();

        // URL
        $url = config('modulesconfig.verification.server_url') . '/PrepaidCardServices/v1/Crypto/sign';

        $result = Http::withToken($token)
            ->withOptions(['verify' => false])
            ->post($url, ['payload' => base64_encode($data)]);

        return json_decode($result, true)['signature'];
    }
}