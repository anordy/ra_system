<?php

namespace App\Enum;

use ReflectionClass;

class PropertyTypeStatus implements Status
{
    const CONDOMINIUM = 'condominium';
    const RESIDENTIAL_STOREY = 'residential_storey';
    const STOREY_BUSINESS = 'storey_business';
    const HOTEL = 'hotel';
    const OTHER = 'other';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
