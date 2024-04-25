<?php

namespace App\Enum;

use ReflectionClass;

class StatementStatus implements Status
{
    const PENDING = 'pending';
    const SUCCESS = 'success';
    const FAILED = 'failed';
    const FAILED_SUBMISSION = 'failed-submission';
    const SUBMITTED = 'submitted';

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}