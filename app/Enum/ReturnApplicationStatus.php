<?php

namespace App\Enum;

use ReflectionClass;

class ReturnApplicationStatus implements Status
{
    const DRAFT = 'draft';
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const CORRECTION = 'correction';
    const REJECTED = 'rejected';

    const ADJUSTED = 'adjusted';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}