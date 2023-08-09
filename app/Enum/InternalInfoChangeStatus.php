<?php

namespace App\Enum;

use ReflectionClass;

class InternalInfoChangeStatus implements Status
{
    const PENDING = 'pending';
    const APPROVED = 'approved';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}