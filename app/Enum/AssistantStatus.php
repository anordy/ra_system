<?php

namespace App\Enum;

use ReflectionClass;

class AssistantStatus implements Status
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const DRAFT = 'draft';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
