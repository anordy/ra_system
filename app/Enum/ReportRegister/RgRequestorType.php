<?php

namespace App\Enum\ReportRegister;

use ReflectionClass;
use App\Enum\Status;


class RgRequestorType implements Status
{
    const TAXPAYER = 1;
    const STAFF = 2;

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}