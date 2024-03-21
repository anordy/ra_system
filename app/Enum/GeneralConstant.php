<?php

namespace App\Enum;

use ReflectionClass;

class GeneralConstant implements Status
{
    const ZERO = '0';
    const ONE = '1';
    const TWO = '2';

    const ONE_INT = 1;
    const ZERO_INT = 0;
    const OWNED = 'Owned';
    const RENTED = 'Rented';
    const ALL = 'all';
    const YES = 'yes';
    const NO = 'no';
    const LOCATION = 'location';
    const NOT_APPLICABLE = 'not_applicable';
    const PAID = 'paid';
    const REVERSED = 'reversed';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
