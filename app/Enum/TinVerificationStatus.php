<?php

namespace App\Enum;

use ReflectionClass;

class TinVerificationStatus implements Status
{
    const PENDING = 'pending';

    const APPROVED = 'approved';
    const UNVERIFIED = 'unverified';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}