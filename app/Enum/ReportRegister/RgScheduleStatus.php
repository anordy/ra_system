<?php

namespace App\Enum\ReportRegister;

use App\Enum\Status;
use ReflectionClass;


class RgScheduleStatus implements Status
{
    const CANCELLED = 'cancelled';
    const SUCCESS = 'success';
    const FAILED = 'failed';
    const PENDING = 'pending';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
