<?php

namespace App\Enum;

use ReflectionClass;

class SurveySolutionType implements Status
{
    const TIN = 'tin';
    const MOBILE = 'phoneNumber';
    const ZRA_NUMBER = 'zra_number';
    const ZRA_REFERENCE_NO = 'zra_reference_no';
    const PASSPORT = 'passport';
    const ZANID = 'zanID';
    const NIDA = 'nida';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}