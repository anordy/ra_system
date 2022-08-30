<?php

namespace App\Enum;

use ReflectionClass;

class AppStepStatus implements Status
{
    const NORMAL = 'normal';
    const WAIVER = 'waiver';
    const EXTENSION = 'extension';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
    