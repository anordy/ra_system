<?php

namespace App\Enum\ReportRegister;

use ReflectionClass;
use App\Enum\Status;


class RgStatus implements Status
{
    const SUBMITTED = 'submitted';
    const IN_PROGRESS = 'in-progress';
    const RESOLVED = 'resolved';
    const ON_HOLD = 'on-hold';
    const EXTERNAL_SUPPORT = 'external-support';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
