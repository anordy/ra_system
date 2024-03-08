<?php

namespace App\Enum;

use ReflectionClass;

class CustomMessage implements Status
{
    const ERROR = 'Something went wrong, please contact your system administrator for support.';
    const ARE_YOU_SURE = 'Are you sure you want to complete this action?';

    public static function error(){
        return self::ERROR;
    }
    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}