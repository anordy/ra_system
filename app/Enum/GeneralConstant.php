<?php

namespace App\Enum;

use ReflectionClass;

class GeneralConstant implements Status
{
    const CHASSIS = 'chassis';
    const ERROR = 'error';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const QUESTION = 'question';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}