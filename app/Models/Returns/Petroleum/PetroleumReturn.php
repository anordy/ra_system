<?php

namespace App\Models\Returns\Petroleum;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\FinancialYear;
use App\Models\SevenDaysFinancialMonth;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetroleumReturn extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function configReturns()
    {
        return $this->hasMany(PetroleumReturnItem::class, 'return_id');
    }

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function businessLocation() {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }

    public function taxtype() {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

    public function financialYear() {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function financialMonth(){
        return $this->belongsTo(SevenDaysFinancialMonth::class, 'financial_month_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'filed_by_id');
    }

    public function bill(){
        return $this->morphOne(ZmBill::class, 'billable');
    }

    public function petroleumPenalties(){
        return $this->hasMany(PetroleumPenalty::class, 'return_id');
    }
}
