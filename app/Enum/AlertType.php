<?php

namespace App\Enum;

use ReflectionClass;

class AlertType implements Status
{
    const SUCCESS = 'success';
    const ERROR = 'error';
    const WARNING = 'warning';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}