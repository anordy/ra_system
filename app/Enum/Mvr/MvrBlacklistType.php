<?php

namespace App\Enum\Mvr;

use App\Enum\Status;
use ReflectionClass;

class MvrBlacklistType implements Status
{
    public const MVR = 'motor-vehicle';
    public const DL = 'drivers-license';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
