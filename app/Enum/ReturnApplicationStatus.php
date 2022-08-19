<?php

namespace App\Enum;

use ReflectionClass;

class ReturnApplicationStatus implements Status
{
    const DRAFT = 'draft';
    const SUBMITTED = 'submitted';
    const SELF_ASSESSMENT = 'self-assessment';
    const ADJUSTED = 'adjusted';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}