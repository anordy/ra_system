<?php

namespace App\Enum;

use ReflectionClass;

class PropertyOwnershipTypeStatus implements Status
{
    const PRIVATE = 'private';
    const RELIGIOUS = 'religious-institution';
    const GOVERNMENT = 'government-institution';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
