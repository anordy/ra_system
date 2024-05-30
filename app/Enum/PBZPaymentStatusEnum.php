<?php

namespace App\Enum;

use ReflectionClass;

class PBZPaymentStatusEnum implements Status
{
    const PAID = 'paid';
    const PAID_PARTIALLY = 'paid-partially';
    const PENDING = 'pending';
    const CANCELLED = 'cancelled';
    const FAILED = 'failed';
    const REVERSED = 'reversed';
    const PAID_INCORRECTLY = 'paid-incorrectly';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}