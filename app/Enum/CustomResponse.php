<?php

namespace App\Enum;

use ReflectionClass;

class CustomResponse implements Status
{
    const ERROR = 'Something went wrong, please contact your system administrator for support.';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}