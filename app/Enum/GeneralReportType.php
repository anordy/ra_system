<?php

namespace App\Enum;

use ReflectionClass;

class GeneralReportType implements Status
{
    const TAXPAYER_REGISTRATION = 'TAX PAYER REGISTRATION';
    const RETURNS = 'RETURNS';
    const INFRASTRUCTURE = 'INFRASTRUCTURE';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}