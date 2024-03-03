<?php

namespace App\Traits;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

trait PhoneUtil
{
    /**
     * @throws NumberParseException
     */
    public function getE164Format($number)
    {
        if (!$number){
            return $number;
        }
        $phoneUtil = PhoneNumberUtil::getInstance();
        $numberProto = $phoneUtil->parse($number, "TZ");
        return $phoneUtil->format($numberProto, PhoneNumberFormat::E164);
    }

    public function getNationalFormat($number)
    {
        if (!$number){
            return $number;
        }
        $phoneUtil = PhoneNumberUtil::getInstance();
        $numberProto = $phoneUtil->parse($number, "TZ");
        return '0' . $numberProto->getNationalNumber();
    }
}
