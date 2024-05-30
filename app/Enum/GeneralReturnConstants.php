<?php

namespace App\Enum;

use ReflectionClass;

class GeneralReturnConstants implements Status
{
    const PERCENTAGE = 'percentage';
    const FIXED = 'fixed';


    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
