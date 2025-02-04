<?php

namespace App\Enum\Mvr;

use App\Enum\Status;
use ReflectionClass;

class MvrBlacklistInitiatorType implements Status
{

    public const ZRA = 'zra';
    public const ZARTSA = 'zartsa';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
