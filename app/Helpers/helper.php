<?php

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Region;
use App\Models\District;
use App\Models\ApprovalLevel;
use App\Models\EducationLevel;
use App\Models\UserApprovalLevel;
use Illuminate\Support\Facades\Auth;
use App\Models\BusinessTaxTypeChange;

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

function fmCurrency($amount)
{
    return number_format(floatval($amount), 2);
}

function convertMoneyToWord($number)
{
    if (class_exists(NumberFormatter::class)) {
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return $f->format($number);
    } else {
        return $number;
    }
}

function getNumberOrdinal($number)
{
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if ((($number % 100) >= 11) && (($number % 100) <= 13))
        return $number . 'th';
    else
        return $number . $ends[$number % 10];
}

function getUser($id)
{
    $user = User::query()->select('fname', 'lname')->where('id', $id)->first();
    $user = $user->fname . ' ' . $user->lname;
    return $user;
}

function getRole($id)
{
    if (!empty($id)) {
        $role = Role::query()->findOrFail($id);
        $role = $role->name;
        return $role;
    }
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
    if (empty($tax_type_change)) {
        return true;
    } else {
        return false;
    }
}

function isNullOrEmpty($value)
{
    return ($value == null || $value == '');
}

function approvalLevel($level_id, $level_name): bool
{
    $approval = ApprovalLevel::query()->where('id', $level_id)->where('name', $level_name)->first();
    if (!empty($approval)) {
        $level = UserApprovalLevel::where('approval_level_id', $approval->id)
            ->where('user_id', Auth::id())->orderByDesc('id')->first();
        if (!empty($level)) {
            return true;
        }
        return false;
    }
    return false;
}

function compareDualControlValues($old_values, $new_values)
{
    $old_values = strtolower($old_values);
    $new_values = strtolower($new_values);
    return $old_values === $new_values ? true : false;
}

function getDistrict($id)
{
    if (!empty($id)) {
        $district = District::query()->findOrFail($id);
        $district = $district->name;
        return $district;
    }
}

function getRegion($id)
{
    if (!empty($id)) {
        $region = Region::query()->findOrFail($id);
        $region = $region->name;
        return $region;
    }
}

function getWard($id)
{
    if (!empty($id)) {
        $ward = \App\Models\Ward::query()->findOrFail($id);
        $ward = $ward->name;
        return $ward;
    }
}

function getFormattedTinNo($getter){
    if ($getter instanceof \App\Models\BusinessLocation){
        return implode("-", str_split($getter->business->tin, 3));
    }

    if ($getter instanceof \App\Models\Business){
        return implode("-", str_split($getter->tin, 3));
    }

    return '';
}

function formatDate($date)
{
    return Carbon::parse($date)->format('d M Y');
}

function roundOff($amount, $currency)
{
    if ($currency == 'TZS') {
        $amount = round($amount);

        // Get the tens from the amount
        $tens = $amount % 100;

        $roundedTens = ceil($tens / 10) * 10;

        if ($roundedTens > 0 && $roundedTens < 50) {
            $roundedTens = 50;
        } else if ($roundedTens > 50 && $roundedTens < 100) {
            $roundedTens = 100;
        }

        $roundedAmount = $amount - $tens + $roundedTens;
    } else if ($currency == 'USD') {
        $roundedAmount = ceil($amount);
    } else {
        throw new Exception('Invalid currency');
    }

    return $roundedAmount;
}

// Helper function to convert integer to Roman numeral count
function romanNumeralCount($number)
{
    // Define the Roman numeral symbols and their corresponding values
    $symbols = [
        1000 => 'm',
        900 => 'cm',
        500 => 'd',
        400 => 'cd',
        100 => 'c',
        90 => 'xc',
        50 => 'l',
        40 => 'xl',
        10 => 'x',
        9 => 'ix',
        5 => 'v',
        4 => 'iv',
        1 => 'i'
    ];

    // Initialize the result string
    $result = '';

    // Iterate over the symbols array
    foreach ($symbols as $value => $symbol) {
        // Count the number of times the symbol can be added
        $count = intdiv($number, $value);

        // Add the symbol to the result string
        $result .= str_repeat($symbol, $count);

        // Update the remaining number
        $number %= $value;
    }

    return $result;
}