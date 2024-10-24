<?php

namespace App\Enum\NonTaxResident;

use App\Enum\Status;
use ReflectionClass;

class NtrApplicationType implements Status
{
    const INDIVIDUAL = 1;
    const ENTITY = 2;

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}