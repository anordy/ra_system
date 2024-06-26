<?php

namespace App\Enum;

use ReflectionClass;

class MvrTemporaryTransportStatus implements Status
{
    const PENDING = 'pending';
    const APPROVED = 'approved';
    const CORRECTION = 'correction';
    const REJECTED = 'rejected';
    const TRANSPORTED = 'transported';
    const RETURNED = 'returned';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}