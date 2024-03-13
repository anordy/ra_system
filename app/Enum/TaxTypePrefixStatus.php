<?php

namespace App\Enum;

use ReflectionClass;

class TaxTypePrefixStatus implements Status
{
    const A = 'A';
    const B = 'B';
    const C = 'C';
    const E = 'E';
    const F = 'F';
    const N = 'N';
    const K = 'K';
    const J = 'J';
    const I = 'I';
    const G = 'G';
    const D = 'D';
    const O = 'O';
    const P = 'P';
    const M = 'M';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}