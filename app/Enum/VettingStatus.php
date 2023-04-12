<?php

namespace App\Enum;

use ReflectionClass;

class VettingStatus implements Status
{
    const SUBMITTED = 'submitted';
    const VETTED = 'vetted';
    const CORRECTION = 'correction';
    const CORRECTED = 'corrected';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}