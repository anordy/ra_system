<?php

namespace App\Enum;

use ReflectionClass;

class MvrDeRegistrationReasonStatus implements Status
{
    const LOST = 'Lost/Destroyed/Stolen';
    const OUT_OF_ZANZIBAR = 'Sent Permanently Out of Zanzibar';
    const SCRAPPED = 'Scrapped';
    const NOT_UNDER_OBLIGATION = 'No Longer Under Obligation of Registration';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
