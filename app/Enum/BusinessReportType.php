<?php

namespace App\Enum;

use ReflectionClass;

class BusinessReportType implements Status
{
    const NATURE = 'Business-Reg-By-Nature';
    const TAX_TYPE = 'Business-Reg-By-TaxType';
    const WO_ZNO = 'Business-Reg-Without-ZNO';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}