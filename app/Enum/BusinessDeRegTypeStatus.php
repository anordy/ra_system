<?php

namespace App\Enum;

use ReflectionClass;

class BusinessDeRegTypeStatus implements Status
{
    const ALL = 'all';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
    