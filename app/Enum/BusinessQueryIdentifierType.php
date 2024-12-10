<?php

namespace App\Enum;

use ReflectionClass;

class BusinessQueryIdentifierType implements Status
{
    const BUSINESS_NAME = 'business-name';
    const ZTN_NUMBER = 'ztn-number';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
