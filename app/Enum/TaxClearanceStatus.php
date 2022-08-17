<?php

namespace App\Enum;

use ReflectionClass;

class TaxClearanceStatus implements Status
{
    const REQUESTED = 'requested';
    const APPROVED = 'approved';
    const DENIED = 'DENIED';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}