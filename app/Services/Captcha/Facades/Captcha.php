<?php

namespace App\Services\Captcha\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mews\Captcha\Captcha
 */
class Captcha extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'captcha';
    }
}
