<?php

namespace App\Enum;

use ReflectionClass;

class PublicServiceMotorStatus implements Status
{
    const PENDING = 'pending';
    const REGISTERED = 'registered';
    const DEREGISTERED = 'de-registered';
    const TEMP_CLOSED = 'temp-closed';
    const CORRECTION = 'correction';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}