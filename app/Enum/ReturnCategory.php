<?php

namespace App\Enum;

use ReflectionClass;

class ReturnCategory implements Status
{
    const NORMAL = 'normal';
    const DEBT = 'debt';
    const OVERDUE = 'overdue';
    const LATE = 'late';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}