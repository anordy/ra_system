<?php

namespace App\Enum;

use ReflectionClass;

class ReturnStatus implements Status
{
    const ADJUSTED = 'adjusted';
    const SELF_ASSESSMENT = 'self-assessment';
    const SUBMITTED = 'submitted';
    const DRAFT = 'draft';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}