<?php

namespace App\Enum;

use ReflectionClass;

class TransactionType implements Status
{
    public const DEBIT = 'DEBIT';
    public const CREDIT = 'CREDIT';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
