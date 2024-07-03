<?php

namespace App\Enum;

use ReflectionClass;

class GeneralReportType implements Status
{
    const TAXPAYER_REGISTRATION = 'TAX PAYER REGISTRATION';
    const RETURNS = 'RETURNS';
    const INFRASTRUCTURE = 'INFRASTRUCTURE';
    const DEBT = 'DEBT MANAGEMENT';
    const MVR = 'MOTOR VEHICLE REGISTRATION';
    const LAND_LEASE = 'LAND LEASE';
    const TAXPAYER = 'TAX PAYER';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}