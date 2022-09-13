<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandLease extends Model
{
    use HasFactory;

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }

    public function landLeaseHistories()
    {
        return $this->hasMany(LandLeaseHistory::class, 'land_lease_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Taxpayer::class, 'created_by');
    }

    public function zmBills()
    {
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function bills()
    {
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function bill()
    {
        return $this->morphMany(ZmBill::class, 'billable')->latest()->first();
    }

    public function businessLocation()
    {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }

    public function LeasePayments(){
        return $this->hasMany(LeasePayment::class);
    }
}
