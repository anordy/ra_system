<?php

namespace App\Enum;

use ReflectionClass;

class BankReconStatus implements Status
{
    const PENDING = 'pending';
    const AMOUNT_MISMATCH = 'amount-mismatch';
    const SUCCESS = 'success';
    const FAILED = 'failed';
    const NOT_FOUND = 'not-found';
    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
