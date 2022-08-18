<?php

namespace App\Models\Returns\StampDuty;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StampDutyReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function taxType(){
        return $this->belongsTo(TaxType::class);
    }

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function businessLocation(){
        return $this->belongsTo(BusinessLocation::class,'business_location_id');
    }

    public function taxpayer(){
        return $this->morphTo('filed_by');
    }

    public function financialYear(){
        return $this->belongsTo(FinancialYear::class);
    }

    public function financialMonth(){
        return $this->belongsTo(FinancialMonth::class);
    }

    public function items(){
        return $this->hasMany(StampDutyReturnItem::class, 'return_id');
    }

    public function bills(){
        return $this->morphMany(ZmBill::class, 'billable');
    }
    public function payments()
    {
        return $this->bills()->where('status', 'paid');
    }

    public function claimable(){
        $this->morphTo('old_return');
    }

    /*
     * Model attributes
     */
    public function getBillAttribute(){
        return $this->morphMany(ZmBill::class, 'billable')->latest()->first();
    }
}
