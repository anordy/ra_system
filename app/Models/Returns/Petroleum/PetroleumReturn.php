<?php

namespace App\Models\Returns\Petroleum;

use App\Models\ZmBill;
use App\Models\TaxType;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Models\Debts\Debt;
use App\Models\FinancialYear;
use App\Models\BusinessLocation;
use App\Models\Returns\TaxReturn;
use App\Models\SevenDaysFinancialMonth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Returns\Petroleum\PetroleumPenalty;
use App\Models\Returns\Petroleum\PetroleumReturnItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PetroleumReturn extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function configReturns()
    {
        return $this->hasMany(PetroleumReturnItem::class, 'return_id');
    }

    public function debt(){
        return $this->morphOne(Debt::class, 'debt');
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

    public function bills(){
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function getBillAttribute(){
        return $this->morphMany(ZmBill::class, 'billable')->latest()->first();
    }

    public function payments()
    {
        return $this->bills()->where('status', 'paid');
    }

    public function penalties(){
        return $this->hasMany(PetroleumPenalty::class, 'return_id');
    }

    public function tax_return(){
        return $this->morphOne(TaxReturn::class, 'return');
    }
}
