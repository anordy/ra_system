<?php

use App\Models\ApprovalLevel;
use App\Models\BusinessTaxTypeChange;
use App\Models\EducationLevel;
use App\Models\Role;
use App\Models\User;

function getOperators($owner, $operator_type, $actors)
{
    $data = '';

    if ($owner == 'taxpayer') return 'taxpayer';
    if ($operator_type == 'role') {
        $data = Role::whereIn('id', $actors)->get()->pluck('name')->implode(', ');
    } elseif ($operator_type == 'user') {
        $data = User::whereIn('id', $actors)->get()->pluck('fname', 'lname')->implode(', ');
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

function getUser($id)
{
    $user = User::query()->select('fname', 'lname')->where('id', $id)->first();
    $user = $user->fname . ' ' . $user->lname;
    return $user;
}

function getRole($id)
{
    $user = User::query()->findOrFail($id);
    $role = $user->role->name;
    return $role;
}

function getEducation($id)
{
    $level = EducationLevel::query()->findOrFail($id);
    $level = $level->name;
    return $level;
}

function checkIfTaxTypeSaved($return)
{
    $tax_type_change = BusinessTaxTypeChange::query()
        ->where('business_id', $return->business_id)
        ->where('from_tax_type_id', $return->tax_type_id)
        ->first();
    if (empty($tax_type_change))
    {
        return true;
    }
    else{
        return false;
    }
}

function isNullOrEmpty($value){
    return ($value == null || $value == '');
}

function approvalLevel($role_id, $level)
{
    $approval = ApprovalLevel::query()->where('name', $level)->first();
    if (!empty($approval)) {
        if (!empty($approval->role_level)) {
            $role_levels = $approval->role_level;
            $id = [];
            foreach ($role_levels as $row) {
                $id[] = $row->role_id;
            }
            if (in_array($role_id, $id)) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
    return false;
}