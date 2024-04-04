<?php

namespace App\Enum;

use ReflectionClass;

class CustomMessage implements Status
{
    const ERROR = 'Something went wrong, please contact your system administrator for support.';
    const ARE_YOU_SURE = 'Are you sure you want to complete this action?';

    const RECEIVE_PAYMENT_SHORTLY = 'Your request was submitted, you will receive your payment information shortly.';
    const FAILED_TO_GENERATE_CONTROL_NUMBER = 'Control number could not be generated, please try again later.';
    const FEE_NOT_CONFIGURED = 'Fee for the selected registration type is not configured';

    public static function error(){
        return self::ERROR;
    }
    static function getConstants(): array
    {
        $oClass = new ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}