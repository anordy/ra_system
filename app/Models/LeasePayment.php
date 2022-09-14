<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeasePayment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'lease_payments';
    protected $guarded = [];

    public function landLease(){
        return $this->belongsTo(LandLease::class, 'land_lease_id');
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }
    
    public function bills()
    {
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function zmBills()
    {
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function getBillAttribute()
    {
        return $this->morphMany(ZmBill::class, 'billable')->latest()->first();
    }

    public function financialYear() {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function financialMonth(){
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id');
    }

    public function penalties(){
        return $this->hasMany(LeasePaymentPenalty::class);
    }

    public function totalPenalties(){
        return $this->penalties->sum('penalty_amount');
    }

    public function debt(){
        return $this->hasOne(LandLeaseDebt::class);
    }
}
