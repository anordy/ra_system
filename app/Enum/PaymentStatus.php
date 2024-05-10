<?php

namespace App\Enum;

use ReflectionClass;

class PaymentStatus implements Status
{
    const PAID = 'paid';
    const PARTIALLY = 'partially';
    const PARTIALLY_PAID = 'partially-paid';
    const PENDING = 'pending';

    const CN_GENERATED = 'control-number-generated';
    const CANCELLED = 'cancelled';
    const FAILED = 'failed';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}