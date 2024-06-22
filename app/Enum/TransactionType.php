<?php

namespace App\Enum;

use ReflectionClass;

class TransactionType implements Status
{
    const CREDIT = 'CR';
    const DEBIT = 'DR';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
