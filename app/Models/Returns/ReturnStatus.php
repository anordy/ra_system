<?php

namespace App\Models\Returns;

use App\Enum\Status;
use ReflectionClass;

class ReturnStatus implements Status
{
    const SUBMITTED = 'submitted';
    const CN_GENERATING = 'control-number-generating';
    const CN_GENERATED = 'control-number-generated';
    const CN_GENERATION_FAILED = 'control-number-generating-failed';
    const PAID_PARTIALLY = 'paid-partially';
    const COMPLETE = 'complete';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
