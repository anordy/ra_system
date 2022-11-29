<?php

namespace App\Enum;

use ReflectionClass;

class BillStatus implements Status
{
    const SUBMITTED = 'submitted';
    const PENDING = 'pending';
    const FAILED = 'failed';    
    const CN_GENERATING = 'control-number-generating';
    const CN_GENERATED = 'control-number-generated';
    const CN_GENERATION_FAILED = 'control-number-generating-failed';
    const PAID_PARTIALLY = 'paid-partially';
    const COMPLETED_PARTIALLY = 'completed-partially';
    const COMPLETE = 'complete';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
    