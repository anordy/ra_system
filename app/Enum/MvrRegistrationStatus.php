<?php

namespace App\Enum;

use ReflectionClass;

class MvrRegistrationStatus implements Status
{
    const PENDING = 'PENDING';
    const APPROVED = 'APPROVED';
    const CORRECTION = 'CORRECTION';
    const STATUS_REGISTERED = 'REGISTERED';
    const STATUS_PLATE_NUMBER_PRINTING = 'PLATE NUMBER PRINTING';
    const STATUS_PENDING_PAYMENT = 'FEE PAYMENT';
    const STATUS_INSPECTION = 'INSPECTION';
    const STATUS_DE_REGISTERED = 'DE REGISTERED';
    const STATUS_RETIRED = 'RETIRED';
    const STATUS_CHANGE = 'STATUS CHANGE';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
