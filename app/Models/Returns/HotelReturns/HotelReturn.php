<?php

namespace App\Models\Returns\HotelReturns;

use App\Models\ZmBill;
use App\Models\TaxType;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Models\FinancialYear;
use App\Models\FinancialMonth;
use App\Models\BusinessLocation;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Returns\HotelReturns\HotelReturnPenalty;

class HotelReturn extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function items(){
        return $this->hasMany(HotelReturnItem::class, 'return_id');
    }

    public function configReturns(){
        return $this->hasMany(HotelReturnItem::class, 'return_id');
    }

    public function business() {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function businessLocation() {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'filed_by_id');
    }

    public function taxtype() {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

    public function financialYear() {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function financialMonth(){
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id');
    }

    public function bills(){
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function getBillAttribute(){
        return $this->morphMany(ZmBill::class, 'billable')->latest()->first();
    }

    public function penalties(){
        return $this->hasMany(HotelReturnPenalty::class, 'return_id');
    }

}
