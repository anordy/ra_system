<?php

namespace App\Enum;

use ReflectionClass;

class UnitUsageTypeStatus implements Status
{
    const RESIDENTIAL = 'residential';
    const BUSINESS = 'business';
    const BOTH = 'both';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
