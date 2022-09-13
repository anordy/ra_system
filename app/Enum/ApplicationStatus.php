<?php

namespace App\Enum;

use ReflectionClass;

class ApplicationStatus implements Status
{
    const NORMAL = 'normal';
    const WAIVER = 'waiver';
    const INSTALLMENT = 'installment';
    const EXTENSION = 'extension';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}