<?php

namespace App\Enum;

use ReflectionClass;

class GeneralReportType implements Status
{
    const RETURNS = 'RETURNS';
    const INFRASTRUCTURE = 'INFRASTRUCTURE';
    const DEBT = 'DEBT MANAGEMENT';
    const MVR = 'MOTOR VEHICLE REGISTRATION';
    const LAND_LEASE = 'LAND LEASE';
    const TAXPAYER = 'TAX PAYER';
    const BUSINESS = 'BUSINESS';
    const PROPERTY_TAX = 'PROPERTY TAX';
    const RESEARCH_REPORT = 'RESEARCH REPORT';
    const TAX_CLAIMS = 'TAX CLAIMS';
    const RELIEF = 'RELIEF';
    const COMPLIANCE = 'COMPLIANCE';
    const TAX_AUDIT = 'TAX AUDIT';
    const TAX_INVESTIGATION = 'TAX INVESTIGATION';
    const DISPUTE = 'DISPUTES';
    const DST = 'DIGITAL SERVICES TAXATION';
    const REPORT_REGISTER = 'REPORT REGISTER';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}