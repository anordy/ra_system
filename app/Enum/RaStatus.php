<?php

namespace App\Enum;

use ReflectionClass;

class RaStatus implements Status
{
    CONST PENDING = 'PENDING';
    CONST APPROVED = 'APPROVED';
    CONST REJECTED = 'REJECTED';
    CONST CORRECTION = 'CORRECTION';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
    