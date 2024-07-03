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

}
