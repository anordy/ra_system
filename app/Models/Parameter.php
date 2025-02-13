<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const START_DATE = 'start_date';
    public const END_DATE = 'end_date';
    public const FINANCIAL_YEAR = 'financial_year';
    public const FINANCIAL_MONTH = 'f_month';

    public const TAX_TYPE = 'tax_type';
    public const REGION = 'region';
    public const DISTRICT = 'district';
    public const TAX_REGION = 'tax_region';
    public const DEPARTMENT = 'department';

    public const REGION_NAME = 'region_name';
    public const CODE = 'code';
    public const PAYMENT_STATUS = 'payment_status';
    public const DYNAMIC_DATE = 'dynamic_date';
    public const ZTN_NUMBER = 'ztn_number';
    public const ZTN_LOCATION_NUMBER = 'zin';
    public const RATE = 'rate';
    public const PROJECT_ID = 'project_id';
    public const DEPARTMENT_NAME = 'department_name';
    public const TAX_REGION_NAME = 'tax_region_name';
    public const DST_BUSINESS_TYPE = 'business_type';
    public const RG_CATEGORY_ID = 'rg_category_id';
    public const RG_SUB_CATEGORY_ID = 'sub_category_id';

    // Drivers license
    public const DL_LOCATION = 'location';
    public const DL_STATUS = 'status';
    public const DL_TYPE = 'type';
    public const DL_IS_BLOCKED = 'is_blocked';
    public const DL_PAYMENT_STATUS = 'payment_status';
    public const DL_DURATION = 'duration_id';

    // Mvr registration
    public const MVR_PAYMENT_STATUS = 'mvr_payment_status';
    public const MVR_VEHICLE_REGISTRATION_TYPE = 'mvr_registration_type';
    public const MVR_VEHICLE_CATEGORY = 'mvr_vehicle_category';
    public const MVR_LOCATION = 'mvr_location';
    public const MVR_CLASS = 'mvr_class';

    public const MVR_BLACKLIST = 'mvr_is_blocked';

}
