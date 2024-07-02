<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SystemSetting extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    const PASSWORD_EXPIRATION_DURATION = 'password-expiration-duration';
    const MAXIMUM_NUMBER_OF_ATTEMPTS = 'max-login-attempts';
    const LOGIN_DECAY_MINUTES = 'login-decay-minutes';
    const GENERAL_COMMISSIONER_SIGN = 'general-commissioner-sign';
    const GENERAL_COMMISSIONER_NAME = 'general-commissioner-name';
    const DURATION_BEFORE_DELETE_DRAFT_BUSINESSES = 'duration-before-delete-draft-businesses';
    const STAMP_DUTY_MIN = 'stamp-duty-minimum-filling-amount';

    const INPUT_TIME = 'time';
    const INPUT_FILE = 'file';
    const INPUT_OPTIONS = 'options';
    const INPUT_RADIO = 'radio';
    const INPUT_TEXT = 'text';
    const INPUT_NUMBER = 'number';
    const ENABLE_OTP_ALTERNATIVE = 'enable-otp-alternative';

    const RESIDENTIAL_STOREY_BUILDING = 'residential-storey-building-property-tax-amount';
    const CONDOMINIUM_BUILDING = 'condominium-building-property-tax-amount';
    const STOREY_BUSINESS_BUILDING = 'storey-business-building-property-tax-amount';
    const OTHER_BUSINESS_BUILDING = 'other-business-building-property-tax-amount';

    const NUMBER_OF_TIMES_INTEREST_IS_COMPOUNDED_IN_PROPERTY_TAX_PER_YEAR = 'number-of-times-interest-compounded-in-property-tax-per-year';
    const NUMBER_OF_TIMES_INTEREST_IS_COMPOUNDED_IN_PUBLIC_SERVICE_PER_YEAR = 'number-of-times-interest-compounded-in-public-service-per-year';
    const PUBLIC_SERVICE_INTEREST_RATE = 'public-service-interest-rate';
    const PROPERTY_TAX_INTEREST_RATE = 'property-tax-interest-rate';
    const PROPERTY_TAX_TIME_VARIABLE = 'property-tax-time-variable';
    const TAX_REFUND_RATE = 'tax-refund-rate';
    const PO_BOX = 'po-box';
    const TEL = 'tel-number';
    const FAX = 'fax-number';
    const OPERATING_OFFICE = 'operating-office';
    const EMAIL = 'operating-email';
    const INSTITUTION_NAME = 'institution-name';
    const INSTITUTION_LOCATION = 'institution-location';
    const INSTITUTION_WEBSITE = 'institution-website';

    const OVERRIDE_VFMS_LINK = 'override-vfms-link';
    const BIOMETRIC_STATUS = 'biometric-status';
    const LAST_CONFIGURATIONS_CHECK = 'last-configurations-check';


    protected $guarded = [];
    
    public function system_setting_category(){
        return $this->belongsTo(SystemSettingCategory::class, 'system_setting_category_id');
    }

    public function scopeCertificatePath($query){
        return $query->where('code', SystemSetting::GENERAL_COMMISSIONER_SIGN)->where('is_approved', 1)->value('value') ?? null;
    }
    public function scopeCommissinerFullName($query){
        return $query->where('code', SystemSetting::GENERAL_COMMISSIONER_NAME)->where('is_approved', 1)->value('value') ?? null;
    }
}
