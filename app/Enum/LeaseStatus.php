<?php

namespace App\Enum;

use ReflectionClass;

class LeaseStatus implements Status
{
    const PENDING = 'pending';
    const DEBT = 'debt';
    const IN_ADVANCE_PAYMENT = 'in_advance_payment';
    const IN_PAYMENT = 'in_payment';
    const LATE_PAYMENT = 'late_payment';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
    