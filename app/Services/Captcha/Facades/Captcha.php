<?php

namespace App\Services\Captcha\Facades;

use Illuminate\Support\Facades\Facade;


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
