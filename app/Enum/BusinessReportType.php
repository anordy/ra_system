<?php

namespace App\Enum;

use ReflectionClass;

class BusinessReportType implements Status
{
    const CHANNEL = 'Channel';
    const SOURCE = 'Source';
    const REVENUE_LOSS = 'Revenue Loss';
    const OVERCHARGING = 'Overcharging';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}