<?php

namespace App\Enum;

use ReflectionClass;

class QuantityCertificateStatus implements Status
{
    const DRAFT = 'draft';
    const PENDING = 'pending';
    const CORRECTION = 'correction';
    const FILLED = 'filled';
    const ACCEPTED = 'accepted';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}