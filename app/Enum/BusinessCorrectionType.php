<?php

namespace App\Enum;

use ReflectionClass;

class BusinessCorrectionType implements Status
{
    const INFORMATION = "business-information-step";
    const LOCATION = "business-location-step";
    const HOTEL = "hotel-information-step";
    const CONTACT = "contact-person-step";
    const BANK = "bank-account-info-step";
    const ATTACHMENTS = "attachments-upload-step";

    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
