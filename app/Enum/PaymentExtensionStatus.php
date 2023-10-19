<?php

namespace App\Enum;

use ReflectionClass;

class PaymentExtensionStatus implements Status
{
    public const DRAFT = 'draft';
    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const CORRECTION = 'correction';
    public const REJECTED = 'rejected';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
