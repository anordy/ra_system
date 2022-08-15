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
    const SUBMITTED = 'submitted';
    const SELF_ASSESSMENT = 'self-assessment';
    const DISPUTE = 'dispute';
    const CLAIM = 'claim';
    const ADJUSTED = 'adjusted';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}