<?php

use App\Models\ApprovalLevel;
use App\Models\BusinessTaxTypeChange;
use App\Models\District;
use App\Models\EducationLevel;
use App\Models\ExchangeRate;
use App\Models\InterestRate;
use App\Models\Region;
use App\Models\Role;
use App\Models\User;
use App\Models\UserApprovalLevel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaxType
{
    const VAT = 'vat';
    const HOTEL = 'hotel-levy';
    const RESTAURANT = 'restaurant-levy';
    const TOUR_OPERATOR = 'tour-operator-levy';
    const LAND_LEASE = 'land-lease';
    const PUBLIC_SERVICE = 'public-service';
    const EXCISE_DUTY_MNO = 'excise-duty-mno';
    const EXCISE_DUTY_BFO = 'excise-duty-bfo';
    const PETROLEUM = 'petroleum-levy';
    const AIRPORT_SERVICE_SAFETY_FEE = 'airport-service-safety-fee';
    const AIRPORT_SERVICE_CHARGE = 'airport-service-charge';
    const AIRPORT_SAFETY_FEE = 'airport-safety-fee';
    const SEAPORT_SERVICE_TRANSPORT_CHARGE = 'seaport-service-transport-charge';
    const SEAPORT_SERVICE_CHARGE = 'seaport-service-charge';
    const SEAPORT_TRANSPORT_CHARGE = 'seaport-transport-charge';
    const TAX_CONSULTANT = 'tax-consultant';
    const STAMP_DUTY = 'stamp-duty';
    const LUMPSUM_PAYMENT = 'lumpsum-payment';
    const ELECTRONIC_MONEY_TRANSACTION = 'electronic-money-transaction';
    const MOBILE_MONEY_TRANSFER = 'mobile-money-transfer';
    const PENALTY = 'penalty';
    const INTEREST = 'interest';
    const INFRASTRUCTURE = 'infrastructure';
    const RDF = 'road-development-fund';
    const ROAD_LICENSE_FEE = 'road-license-fee';
    const AUDIT = 'audit';
    const VERIFICATION = 'verification';
    const DISPUTES = 'disputes';
    const WAIVER = 'waiver';
    const OBJECTION = 'objection';
    const WAIVER_OBJECTION = 'waiver-and-objection';
    const INVESTIGATION = 'investigation';
    const GOVERNMENT_FEE = 'government-fee';
    const DEBTS = 'debts';
    const AIRBNB = 'hotel-airbnb';

    const PROPERTY_TAX = 'property-tax';
}

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
        $role = Role::query()->findOrFail($id, ['name']);
        return $role->name;
    }

    return 'N/A';
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
        ->latest()
        ->first();

    if (empty($tax_type_change)) {
        return true;
    } else {
        if ($tax_type_change->status == 'pending') {
            return true;
        }
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

function getFormattedTinNo($getter)
{
    if ($getter instanceof \App\Models\BusinessLocation) {
        return implode("-", str_split($getter->business->tin, 3));
    }

    if ($getter instanceof \App\Models\Business) {
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

function getHotelStarByBusinessId($business_id)
{
    $hotel_star = DB::table('business_hotels as b')
        ->leftJoin('hotel_stars as h', 'b.hotel_star_id', '=', 'h.id')
        ->where('b.business_id', '=', $business_id)
        ->select('h.infrastructure_charged', 'no_of_stars')->first();
    return $hotel_star;
}

function getTaxTypeName($taxTypeId)
{
    return \App\Models\TaxType::select('name')->find($taxTypeId)->name ?? '';
}

function getSubVatName($subVatId)
{
    return \App\Models\Returns\Vat\SubVat::select('name')->find($subVatId)->name ?? '';
}


function formatEnum($string)
{
    $string = str_replace('_', ' ', $string);
    return ucwords($string);
}

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

function exchangeRate()
{
    $exchangeRate = ExchangeRate::where('currency', 'USD')
        ->whereRaw("TO_CHAR(exchange_date, 'mm') = TO_CHAR(SYSDATE, 'mm') AND TO_CHAR(exchange_date, 'yyyy') = TO_CHAR(SYSDATE, 'yyyy')")
        ->firstOrFail();
    return $exchangeRate;
}

function interestRate()
{
    $interestRate = InterestRate::where('year', Carbon::now()->year)->firstOrFail();
    return number_format($interestRate->rate, 4);

}

function getSourceName($model)
{
    if ($model == \App\Models\Returns\TaxReturn::class) {
        return 'Return';
    } else if ($model == \App\Models\TaxRefund\TaxRefund::class) {
        return 'Tax Refund';
    } else if ($model == \App\Models\TaxAssessments\TaxAssessment::class) {
        return 'Assessment';
    } else if ($model == App\Models\PublicService\PublicServiceReturn::class) {
        return 'Public Service';
    } else if ($model == \App\Models\Investigation\TaxInvestigation::class) {
        return 'Tax Investigation';
    } else if ($model == \App\Models\TaxAudit\TaxAudit::class) {
        return 'Tax Audit';
    } else {
        return 'N/A';
    }
}


function getSignature($modelInstance)
{
    if (get_class($modelInstance) === \App\Models\BusinessLocation::class) {
        $approvedOn = $modelInstance->approved_on ?? $modelInstance->verified_at;
    } else if (get_class($modelInstance) === \App\Models\TaxClearanceRequest::class) {
        $approvedOn = $modelInstance->approved_on;
    } else if (get_class($modelInstance) === \App\Models\WithholdingAgent::class) {
        $approvedOn = $modelInstance->approved_on;
    } else if (get_class($modelInstance) === \App\Models\TaxAgent::class) {
        if ($modelInstance->is_first_application == 1) {
            $start_date = $modelInstance->app_first_date;
        } else {
            $start_date = $modelInstance->renew_first_date;
        }
        $approvedOn = $start_date;
    } else {
        $approvedOn = null;
    }

    // Get Signature
    if ($approvedOn) {
        return \App\Models\CertificateSignature::query()
            ->select(['title', 'name', 'image'])
            ->where('is_approved', \App\Enum\GeneralConstant::ONE_INT)
            ->where('start_date', '<=', $approvedOn)
            ->where('end_date', '>=', $approvedOn)
            ->latest()
            ->first();
    }

    return null;


}

function custom_dispatch($job, $time = null): int
{
    if ($time) {
        return app(\Illuminate\Contracts\Bus\Dispatcher::class)->dispatch($job->delay($time));
    }
    return app(\Illuminate\Contracts\Bus\Dispatcher::class)->dispatch($job);
}

function getDepartment($id)
{
    if (!empty($id)) {
        $department = \App\Models\ReportRegister\Department::find($id, ['name']);

        if ($department) {
            return $department->name;
        }
    }
    return 'N/A';
}