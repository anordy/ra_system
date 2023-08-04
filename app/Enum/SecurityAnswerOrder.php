<?php

namespace App\Enum;

use ReflectionClass;

class SecurityAnswerOrder implements Status
{
    const FIRST = 'first';
    const SECOND = 'second';
    const THIRD = 'third';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}