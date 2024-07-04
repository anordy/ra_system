<?php

namespace App\Enum;

use ReflectionClass;

class DlFeeType implements Status
{
    const ADD_CLASS = 'CLASS';
    const FRESH = 'FRESH';
    const RENEW = 'RENEW';
    const DUPLICATE = 'DUPLICATE';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}