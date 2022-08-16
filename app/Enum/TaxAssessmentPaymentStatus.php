<?php

namespace App\Enum;

use ReflectionClass;

class TaxAssessmentPaymentStatus implements Status
{
    const CANCELLED = 'cancelled';
    public const DRAFT = 'draft';
    public const PENDING = 'pending';
    public const APPROVED = 'approved';
    public const CORRECTION = 'correction';
    public const REJECTED = 'rejected';
    public const SUBMITTED = 'submitted';
    public const CN_GENERATING = 'control-number-generating';
    public const CN_GENERATED = 'control-number-generated';
    public const CN_GENERATION_FAILED = 'control-number-generating-failed';
    public const PAID_PARTIALLY = 'paid-partially';
    public const COMPLETE = 'complete';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
