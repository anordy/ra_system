<?php

use App\Services\EncryptEnv\Action\Decrypt;
use Illuminate\Support\Env;

if (!function_exists('secEnv')) {

    /**
     * secEnv Helper function
     *
     * @param $name string
     * @param $fallback string
     *
     * @return string
     */
    function secEnv($name, $fallback = '')
    {
        $data = Env::get($name, $fallback);

        if (str_contains($data, "!UBX:")) {
            return str_replace("!UBX:", '', $data);
        }

        if (str_contains($data, "ENC:")) {
            $configval = (new Decrypt)->get($name);
            return isset($configval) ? $configval : $fallback;
        }

        return $data;
    }
}
