<?php

namespace App\Enum\ReportRegister;

use ReflectionClass;
use App\Enum\Status;


class RgAssignmentStatus implements Status
{
    const ASSIGNED = 'assigned';
    const RE_ASSIGNED = 're-assigned';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}