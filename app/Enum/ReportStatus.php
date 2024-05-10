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
    const TAX_TYPE_ID = 'tax_type_id';
    const REGION = 'region';
    const TYPE = 'type';
    const DISTRICT = 'district';
    const WARD = 'ward';

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