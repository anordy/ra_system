<?php

namespace App\Enum;

use ReflectionClass;

class PaymentStatus implements Status
{
    const PAID = 'paid';
    const PARTIALLY = 'partially';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}