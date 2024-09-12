<?php

namespace App\Enum;

use ReflectionClass;

class SmsProvider implements Status
{
    const FAST_HUB = 'FH';
    const WEB_BULK = 'WB';


    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
    