<?php

use App\Models\Role;
use App\Models\User;

function getOperators($owner, $operator_type, $operators)
{
    $data = '';

    if ($owner == 'taxpayer') return 'taxpayer';
    if ($operator_type == 'role') {
        $data = Role::whereIn('id', $operators)->get()->pluck('name')->implode(', ');
    } elseif ($operator_type == 'user') {
        $data = User::whereIn('id', $operators)->get()->pluck('fname', 'lname')->implode(', ');
    }


    return $data;
}

function fmCurrency($amount){
    return number_format(floatval($amount), 2);
}

function convertMoneyToWord($number)
{
    if (class_exists(NumberFormatter::class)){
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return $f->format($number);
    } else {
        return $number;
    }
}

function getNumberOrdinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number % 100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}