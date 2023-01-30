<?php

namespace App\Enum;

use ReflectionClass;

class InstallmentStatus implements Status
{
    public const ACTIVE = 'active';
    public const COMPLETE = 'complete';
    public const CANCELLED = 'cancelled';
    public const REJECTED = 'rejected';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}