<?php

namespace App\Enum;

use ReflectionClass;

class TaxPayerReportCodes implements Status
{
    const GfsData = '100';
    const TaxPayerData = '101';
    const TaxPayerContributionData = '102';
    const TaxPayerForPastTwelveMonth = '103';
    const HotelDataReport = '104';
    const RentingPremisses = '105';
    const FiledTaxPayer = '106';
    const NonFiledTaxPayer = '107';
//    const GfsData = '108';
    const EXCEL = 'EXCEL';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}