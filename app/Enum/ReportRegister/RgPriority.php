<?php

namespace App\Enum\ReportRegister;

use App\Enum\Status;
use ReflectionClass;

class RgPriority implements Status
{
    const LOW = 'low';
    const MEDIUM = 'medium';
    const HIGH = 'high';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}