<?php

namespace App\Enum;

use ReflectionClass;

class ApplicationStep implements Status
{
    const FILING = 'filing';
    const DEBT = 'debt';
    const OVERDUE = 'overdue';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}