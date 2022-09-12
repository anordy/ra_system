<?php

namespace App\Enum;

use ReflectionClass;

class PaymentMethod implements Status
{
    const FULL = 'full';
    const INSTALLMENT = 'installment';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}