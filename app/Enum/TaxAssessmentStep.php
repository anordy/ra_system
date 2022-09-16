<?php

namespace App\Enum;

use ReflectionClass;

class TaxAssessmentStep implements Status
{
    const NORMAL = 'normal';
    const DEBT = 'debt';
    const OVERDUE = 'overdue';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}