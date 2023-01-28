<?php

namespace App\Services\ZanMalipo;

use Illuminate\Support\Facades\Storage;

class ZmSignatureHelper
{

    public static function verifySignature($signature, $content)
    {
        $cert_store = Storage::get("gepg/gepgpubliccertificate.pfx");
        if (is_string($signature) && openssl_pkcs12_read($cert_store, $cert_info, "passpass")) {
            $pubkeyid = openssl_pkey_get_public($cert_info['extracerts'][0]);
            return openssl_verify($content, base64_decode($signature), $pubkeyid, "sha1WithRSAEncryption");
        }
        return false;
    }


    /**
     * @param $content
     * @return string
     * @throws \Exception
     */
    public static function signContent($content)
    {
        $cert_store = Storage::get("gepg/zrbclientprivatekey.pfx");
        if (openssl_pkcs12_read($cert_store, $cert_info, "zpc@2022")) {
            openssl_sign($content, $signature, $cert_info['pkey'], "sha1WithRSAEncryption");
            return base64_encode($signature);
        }
        return null;
    }
}
