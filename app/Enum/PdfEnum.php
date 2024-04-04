<?php

namespace App\Enum;

use ReflectionClass;

class PdfEnum implements Status
{
    const DPI = 150;

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}