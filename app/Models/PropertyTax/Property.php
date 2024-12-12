<?php

namespace App\Models\PropertyTax;

use App\Models\District;
use App\Models\Region;
use App\Models\Street;
use App\Models\Taxpayer;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Models\Ward;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes, WorkflowTrait;

    protected $guarded = [];

    public function region(){
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function district(){
        return $this->belongsTo(District::class, 'district_id');
    }

    public function ward(){
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function street(){
        return $this->belongsTo(Street::class, 'street_id');
    }

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }

    public function responsible(){
        return $this->hasOne(PropertyOwner::class, 'property_id', 'id');
    }

    public function payment(){
        return $this->hasOne(PropertyPayment::class, 'property_id');
    }

    public function payments(){
        return $this->hasMany(PropertyPayment::class, 'property_id');
    }

    public function unit(){
        return $this->hasOne(PropertyUnit::class, 'property_id', 'id');
    }

    public function star(){
        return $this->belongsTo(PropertyTaxHotelStar::class, 'hotel_stars_id');
    }

    public function storeys(){
        return $this->hasMany(PropertyStorey::class, 'property_id');
    }

    public function units(){
        return $this->hasMany(PropertyUnit::class, 'property_id');
    }

    public function agent(){
        return $this->hasOne(PropertyAgent::class, 'property_id', 'id');
    }

    public function ownership(){
        return $this->belongsTo(PropertyOwnershipType::class, 'ownership_type_id');
    }

    public function ledger()
    {
        return $this->morphOne(TaxpayerLedger::class, 'source');
    }

    public function latestPayment(){
        return $this->hasOne(PropertyPayment::class, 'property_id')->latest();
    }
}
