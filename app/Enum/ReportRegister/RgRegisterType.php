<?php

namespace App\Enum\ReportRegister;

use ReflectionClass;
use App\Enum\Status;


class RgRegisterType implements Status
{
    const INCIDENT = 1;
    const TASK = 2;

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}