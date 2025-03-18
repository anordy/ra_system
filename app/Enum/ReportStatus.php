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
    const yearly = 'yearly';

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
    const DPI_150 = 150;

    const LAST_12_MONTHS = 'Last-12-Months';
    const NEXT_12_MONTHS = 'Next-12-Months';
 

    const ONE = 1;
    const TWO = 2;
    const THREE = 3;
    const FOUR = 4;
    const FIVE = 5;
    const SIX = 6;
    const SEVEN = 7;
    const EIGHT = 8;
    const NINE = 9;
    const TEN = 10;
    const ELEVEN = 11;
    const TWELVE = 12;

    const paid = 'paid';
    const approved = 'approved';
    const rejected = 'rejected';
    const pending = 'pending';
    const unpaid = 'unpaid';
    const FILING = 'Filing';
    const PAYMENT = 'Payment';
    const Vat = 'vat';
    const Year = 'year';
    const Period = 'period';
    const both = 'both';
    const date_range = 'date_range';

    const CUSTOM_RANGE = 'Custom Range';

    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}