<?php

namespace App\Enum;

use ReflectionClass;

class BusinessQueryType implements Status
{
    const TAX_TYPE = 'tax-type';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
