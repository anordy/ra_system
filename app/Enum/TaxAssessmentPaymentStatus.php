<?php

namespace App\Enum;

use ReflectionClass;

class TaxAssessmentPaymentStatus implements Status
{
    const PAID = 'paid';
    const PARTIALLY = 'partially';
    const PENDING = 'pending';
    const DRAFT = 'draft';
    const CANCELLED = 'cancelled';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
