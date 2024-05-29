<?php

namespace App\Enum;

use ReflectionClass;

class CharteredType implements Status
{
    const SEA = 'sea';
    const FLIGHT = 'flight';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}