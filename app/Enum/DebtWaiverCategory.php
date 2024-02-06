<?php

namespace App\Enum;

use ReflectionClass;

class DebtWaiverCategory implements Status
{
    public const PENALTY = 'penalty';
    public const INTEREST = 'interest';
    public const BOTH = 'both';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}