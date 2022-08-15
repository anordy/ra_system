<?php

namespace App\Enum;

use ReflectionClass;

class TaxAssessmentStatus implements Status
{
    public const ASSESSMENT = 'assessment';
    public const WAIVER = 'waiver';
    public const OBJECTION = 'objection';
    public const WAIVER_AND_OBJECTION = 'waiver-and-objection';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
