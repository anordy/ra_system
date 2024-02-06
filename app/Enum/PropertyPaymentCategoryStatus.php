<?php

namespace App\Enum;

use ReflectionClass;

class PropertyPaymentCategoryStatus implements Status
{
    const NORMAL = 'normal';
    const EXTENSION = 'extension';
    const DEBT = 'debt';


    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
