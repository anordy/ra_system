<?php

namespace App\Enum;

use ReflectionClass;

class ReturnStatus implements Status
{
    const ADJUSTED = 'adjusted';
    const SELF_ASSESSMENT = 'self-assessment';
    const SUBMITTED = 'submitted';
    const DRAFT = 'draft';

    const PENDING = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}