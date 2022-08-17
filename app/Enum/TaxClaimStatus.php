<?php

namespace App\Enum;

use ReflectionClass;

class TaxClaimStatus implements Status
{
    const DRAFT = 'draft';
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const CORRECTION = 'correction';
    const REJECTED = 'rejected';
    const PAID = 'paid';
    Const PAID_PARTIALLy = 'paid-partially';
    const NO_CLAIM = 'no-claim';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}