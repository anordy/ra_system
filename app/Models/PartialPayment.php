<?php

namespace App\Models;

use App\Models\Installment\InstallmentItem;
use App\Models\TaxAssessments\TaxAssessment;
use Illuminate\Database\Eloquent\Model;

class PartialPayment extends Model
{
    protected $guarded = [];

    public function landLease()
    {
        return $this->belongsTo(LandLease::class, 'payment_id');
    }

    public function installmentItem()
    {
        return $this->belongsTo(InstallmentItem::class, 'payment_id');
    }

    public function taxAssessment()
    {
        return $this->belongsTo(TaxAssessment::class, 'payment_id');
    }

    public function bills()
    {
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function latestBill()
    {
        return $this->morphOne(ZmBill::class, 'billable')->latest();
    }

    public function getBillAttribute()
    {
        return $this->morphMany(ZmBill::class, 'billable')->latest()->first();
    }

    public function secondLatest()
    {
        return $this->morphMany(ZmBill::class, 'billable')->latest();
    }
}
