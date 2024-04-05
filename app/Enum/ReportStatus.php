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

    const TAX_TYPE_ID = 'tax_type_id';
    const REGION = 'region';
    const TYPE = 'type';
    const DISTRICT = 'district';
    const WARD = 'ward';

    const PS_REG_REPORT = 'Registration-Type-Report';
    const PS_PAYMENT_REPORT = 'Payment-Report';
    const TAX_TYPE_CAT_MAIN = 'main';
    const RANGE = 'range';

    const RETURN_REPORT_TYPES = ['Filing', 'Payment'];
    const RETURN_FILLING_TYPES = ['All-Filings', 'On-Time-Filings', 'Late-Filings', 'Tax-Claims', 'Nill-Returns'];

    const ALL_FILINGS = 'All-Filings';
    const ON_TIME_FILINGS = 'On-Time-Filings';
    const LATE_FILINGS = 'Late-Filings';
    const TAX_CLAIMS = 'Tax-Claims';
    const NILL_RETURNS = 'Nill-Returns';
    const RETURN_PAYMENT_TYPES = ['All-Paid-Returns', 'On-Time-Paid-Returns', 'Late-Paid-Returns', 'Unpaid-Returns'];

    const ALL_PAID_RETURNS = 'All-Paid-Returns';
    const ON_TIME_PAID_RETURNS = 'On-Time-Paid-Returns';
    const LATE_PAID_RETURNS = 'Late-Paid-Returns';
    const UNPAID_RETURNS = 'Unpaid-Returns';
    const RETURN_VAT_OPTION_TYPES = ['All-VAT-Returns', 'Hotel-VAT-Returns', 'Electricity-VAT-Returns', 'Local-VAT-Returns'];

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

    const CHARGES_INCLUDED = 'charges-included';
    const CHARGES_EXCLUDED = 'charges-excluded';
    const paid = 'paid';
    const pending = 'pending';
    const unpaid = 'unpaid';
    const FILING = 'Filing';
    const PAYMENT = 'Payment';
    const Vat = 'vat';
    const Year = 'year';
    const Period = 'period';

    const PAYMENT_VAT_TYPES = [

        ['All-VAT-Returns', 'Hotel-VAT-Returns', 'Electricity-VAT-Returns', 'Local-VAT-Returns']
    ];

    const LARGE_TAX_PAYER = 'large-taxpayer';
    const LOCATION = 'location';
    const DEPARTMENT_TYPE = 'department_type';

    const RETURNS = 'Returns';
    const returns = 'returns';
    const ASSESSMENTS = 'Assessments';
    const WAIVER = 'Waiver';
    const INSTALLMENT = 'Installment';
    const DEMAND_NOTICE = 'Demand-Notice';


    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}