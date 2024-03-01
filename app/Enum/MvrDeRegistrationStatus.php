<?php

namespace App\Enum;

use ReflectionClass;

class MvrDeRegistrationStatus implements Status
{
    const PENDING = 'PENDING';
    const CORRECTION = 'CORRECTION';
    const APPROVED = 'APPROVED';
    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
