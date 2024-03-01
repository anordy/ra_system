<?php

namespace App\Enum;

use ReflectionClass;

class MvrDeRegistrationReasonStatus implements Status
{
    const LOST = 'Lost';
    const OUT_OF_ZANZIBAR = 'Out of Zanzibar';
    const SERVIER_ACCIDENT = 'Servier Accident';
    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
