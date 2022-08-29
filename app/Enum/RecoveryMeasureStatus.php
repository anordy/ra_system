<?php

namespace App\Enum;

use ReflectionClass;

class RecoveryMeasureStatus implements Status
{
    public const NONE = 'none';
    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const CORRECTION = 'correction';
    public const REJECTED = 'rejected';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}