<?php

namespace App\Enum;

use ReflectionClass;

class ReportFormats implements Status
{
    const  PDF = 'pdf';
    const  EXCEL = 'xlsx';
    const  CSV = 'csv';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}