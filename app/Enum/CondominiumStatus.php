<?php

namespace App\Enum;

use ReflectionClass;

class CondominiumStatus implements Status
{
    const REGISTERED = 'registered';
    const UNREGISTERED = 'unregistered';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}