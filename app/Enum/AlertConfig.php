<?php

namespace App\Enum;

use ReflectionClass;

class AlertConfig implements Status
{
    public const ERROR_ALERT_DURATION = 120000;//2 minutes

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
