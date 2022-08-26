<?php

namespace App\Enum;

use ReflectionClass;

class Currencies implements Status
{
    const TZS = 'TZS';
    const USD = 'USD';
    const GBP = 'GBP';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}