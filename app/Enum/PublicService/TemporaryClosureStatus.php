<?php

namespace App\Enum\PublicService;

use App\Enum\Status;
use ReflectionClass;

class TemporaryClosureStatus implements Status
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
