<?php

namespace App\Enum;

use ReflectionClass;

class ReportStatus implements Status
{
    const QUARTERLY = 'Quarterly';
    const ANNUAL = 'Annual';
    const SEMI_ANNUAL = 'Semi-Annual';
    const MONTHLY = 'Monthly';
    const YEARLY = 'Yearly';

    const All = 'All';
    const FIRST_SEMI_ANNUAL = '1st-Semi-Annual';
    const SECOND_SEMI_ANNUAL = '2nd-Semi-Annual';

    const FIRST_QUARTER = '1st-Quarter';
    const SECOND_QUARTER = '2nd-Quarter';
    const THIRD_QUARTER = '3rd-Quarter';
    const FOURTH_QUARTER = '4th-Quarter';

    const PAID = 'Paid';
    const UNPAID = 'Unpaid';

    const all = 'all';
    const MONTHS_DESC = [
        1 => "January",
        2 => "February",
        3 => "March",
        4 => "April",
        5 => "May",
        6 => "June",
        7 => "July",
        8 => "August",
        9 => "September",
        10 => "October",
        11 => "November",
        12 => "December"
    ];

    const PS_REG_REPORT = 'Registration-Type-Report';
    const PS_PAYMENT_REPORT = 'Payment-Report';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}