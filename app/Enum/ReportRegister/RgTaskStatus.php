<?php

namespace App\Enum\ReportRegister;

use App\Enum\Status;
use ReflectionClass;


class RgTaskStatus implements Status
{
    const CREATED = 'created';
    const PENDING = 'pending';
    const CLOSED = 'closed';
    const CANCELLED = 'cancelled';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
