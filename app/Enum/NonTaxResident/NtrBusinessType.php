<?php

namespace App\Enum\NonTaxResident;

use App\Enum\Status;
use ReflectionClass;

class NtrBusinessType implements Status
{
    const NON_RESIDENT = 1;
    const ECOMMERCE = 2;

    static function get()
    {
        return collect([['id' => 1, 'name' => 'Non Tax Resident'], ['id' => 2, 'name' => 'Ecommerce']]);
    }

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}