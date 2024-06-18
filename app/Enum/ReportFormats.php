<?php

namespace App\Enum;

use ReflectionClass;

class ReportFormats implements Status
{
    const PDF = 'PDF';
    const EXCEL = 'EXCEL';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}