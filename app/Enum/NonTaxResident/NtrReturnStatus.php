<?php

namespace App\Enum\NonTaxResident;

use App\Enum\Status;
use ReflectionClass;

class NtrReturnStatus implements Status
{
    const FILED = 'filed';
    const CANCELLED = 'cancelled';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}