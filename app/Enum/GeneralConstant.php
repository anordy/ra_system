<?php

namespace App\Enum;

use ReflectionClass;

class GeneralConstant implements Status
{
    const CHASSIS = 'chassis';
    const ERROR = 'error';
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const QUESTION = 'question';

    const ZERO = '0';
    const ONE = '1';
    const TWO = '2';

    const ONE_INT = 1;
    const ZERO_INT = 0;
    const TWO_INT = 2;
    const FOUR_INT = 4;
    const OWNED = 'Owned';
    const RENTED = 'Rented';
    const ALL = 'all';
    const YES = 'yes';
    const NO = 'no';
    const LOCATION = 'location';
    const NOT_APPLICABLE = 'not_applicable';
    const PAID = 'paid';
    const REVERSED = 'reversed';


    const CHASSIS_REGISTRATION = 1;

    const CHASSIS_DE_REGISTRATION = 2;
    const AGENT = 'AGENT';
    const TAXPAYER = 'TAXPAYER';

    const APPLICANT_DETAILS = 'Applicant Details';
    const FRESH = 'fresh';
    const DUPLICATE = 'duplicate';
    const LOST = 'lost';
    const ACTIVE = 'Active';
    const INACTIVE = 'Inactive';

    const MONTH_NUMBER = 'month_number';
    const SINGLE_STATE = 'single_state';
    const STATE_MACHINE = 'state_machine';
    const ADD_CLASS = 'CLASS';
    const VERIFIED = 'verified';

    const INPUT_TYPE_DATE = 'date';
    const INPUT_TYPE_SELECT = 'select';
    const PENDING = 'pending';
    const MAKER = 'Maker';
    const CHECKER = 'Checker';

    const COMPLETE = 'Complete';
    const FAILED = 'Failed';
    const STAFF = 'staff';
    const ROLE = 'role';
    const USER = 'user';

    const QR_CODE_SIZE = 207;
    const QR_CODE_LOGO_SIZE = 36;

    const UTF_8 = 'UTF-8';
    const DPI = 150;
    const APPROVAL_LETTER = 'approval_letter';


    public static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}
