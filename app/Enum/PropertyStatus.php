<?php

namespace App\Enum;

use ReflectionClass;

class PropertyStatus implements Status
{
    const PENDING = 'pending';
    const APPROVED = 'approved';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
