<?php

namespace App\Enum;

use ReflectionClass;

class DisputeStatus implements Status
{
    const DRAFT = 'draft';
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const CORRECTION = 'correction';
    const REJECTED = 'rejected';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}