<?php

namespace App\Enum;

use ReflectionClass;

class PaymentMethod implements Status
{
    const INSTALLMENT = 'installment';
    const FULL = 'full';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
