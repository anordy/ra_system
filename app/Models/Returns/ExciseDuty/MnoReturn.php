<?php

namespace App\Models\Returns\ExciseDuty;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Debts\Debt;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\returns\ExciseDuty\MnoReturnItem;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MnoReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function financialMonth()
    {
        return $this->belongsTo(FinancialMonth::class);
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function items(){
        return $this->hasMany(MnoReturnItem::class, 'mno_return_id');
    }

    public function businessLocation() {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'filed_by_id');
    }

    public function taxType() {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

    public function financialYear() {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function debt(){
        return $this->morphOne(Debt::class, 'debt');
    }

    //to be replaced
    public function bill(){
        return $this->morphOne(ZmBill::class, 'billable');
    }

    //replacer
    public function bills(){
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function payments()
    {
        return $this->bills()->where('status', 'paid');
    }

    public function penalties(){
        return $this->hasMany(MnoPenalty::class,'return_id');
    }
}
