<?php

namespace App\Enum\ReportRegister;

use ReflectionClass;
use App\Enum\Status;


class RgAuditEvent implements Status
{
    const CREATED = 'created';
    const UPDATED = 'updated';
    const CHANGED = 'changed';
    const DELETED = 'deleted';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}