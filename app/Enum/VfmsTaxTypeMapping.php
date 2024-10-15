<?php

namespace App\Enum;

use App\Models\TaxType;
use ReflectionClass;

class VfmsTaxTypeMapping implements Status
{
    const A = TaxType::VAT;
    const B = TaxType::VAT; // VAT in Hotel
    const C = TaxType::HOTEL;
    const D = TaxType::STAMP_DUTY;
    const E = TaxType::RESTAURANT;
    const F = TaxType::TOUR_OPERATOR;
    const G = TaxType::SEAPORT_SERVICE_CHARGE;
    const I = TaxType::VAT; // VAT In Banking and Communication
    const J = TaxType::AIRBNB;
    const Z = TaxType::LUMPSUM_PAYMENT;
    const K = TaxType::VAT; // Vat on Insurance

//    const I = TaxType::B

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}