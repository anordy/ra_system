<?php

namespace App\Enum;

use ReflectionClass;

class DebtPaymentMethod implements Status
{
    public const NORMAL = 'normal';
    public const INSTALLMENT = 'installment';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}