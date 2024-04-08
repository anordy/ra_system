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
    const range = 'range';
    const own = 'own';
    const other = 'other';

    const ALL_BUSINESS = 'All-Business';
    const ISIIC_1 = 'isiic_i';
    const ISIIC_2 = 'isiic_ii';
    const ISIIC_3 = 'isiic_iii';
    const ISIIC_4 = 'isiic_iv';

    const ISIIC1 = 1;
    const ISIIC2 = 2;
    const ISIIC3 = 3;
    const ISIIC4 = 4;
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

    const January = 1;
    const February = 2;
    const March = 3;
    const April = 4;
    const May = 5;
    const June = 6;
    const July = 7;
    const August = 8;
    const September = 9;
    const October = 10;
    const November = 11;
    const December = 12;
    const PS_REG_REPORT = 'Registration-Type-Report';
    const PS_PAYMENT_REPORT = 'Payment-Report';
    const DPI_150 = 150;

    const LAST_12_MONTHS = 'Last-12-Months';
    const NEXT_12_MONTHS = 'Next-12-Months';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}