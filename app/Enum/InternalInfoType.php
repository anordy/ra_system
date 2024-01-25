<?php

namespace App\Enum;

use ReflectionClass;

class InternalInfoType implements Status
{
    const HOTEL_STARS = 'hotel_stars';
    const ISIC = 'isic';
    const TAX_TYPE = 'tax_type';
    const EFFECTIVE_DATE = 'effective_date';
    const LTO = 'lto';
    const ELECTRIC = 'electric';
    const TAX_REGION = 'tax_region';
    const CURRENCY = 'currency';
    const BUSINESS_OWNERSHIP = 'business-ownership';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}