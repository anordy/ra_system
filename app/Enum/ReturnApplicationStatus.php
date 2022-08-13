<?php

namespace App\Enum;

use ReflectionClass;

class ReturnApplicationStatus implements Status
{
    const SUBMITTED = 'submitted';
    const SELF_ASSESSMENT = 'self-assessment';
    const DISPUTE = 'dispute';
    const CLAIM = 'claim';
    const ADJUSTED = 'adjusted';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}