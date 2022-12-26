<?php

namespace App\Models\Returns\Queries;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalePurchase extends Model
{
    use HasFactory;
    protected $table = 'sales_purchases';
    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function businessLocation()
    {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id', 'id');
    }

    public function taxType()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id', 'id');
    }

    public function financialYear() {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function financialMonth(){
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id');
    }
}
