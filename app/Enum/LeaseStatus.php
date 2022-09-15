<?php

namespace App\Enum;

use ReflectionClass;

class LeaseStatus implements Status
{
    const PENDING = 'pending';
    const COMPLETE = 'complete';
    const DEBT = 'debt';
    const CN_GENERATING = 'control-number-generating';
    const CN_GENERATED = 'control-number-generated';
    const CN_GENERATION_FAILED = 'control-number-generating-failed';
    const PAID_PARTIALLY = 'paid-partially';
    const IN_ADVANCE_PAYMENT = 'in_advance_payment';
    const ON_TIME_PAYMENT = 'on_time_payment';
    const LATE_PAYMENT = 'late_payment';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
    