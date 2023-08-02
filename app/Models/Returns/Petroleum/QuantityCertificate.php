<?php

namespace App\Models\Returns\Petroleum;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuantityCertificate extends Model
{
    use HasFactory, WorkflowTrait;

    protected $guarded =  [];

    public function location(){
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function business(){
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function products()
    {
        return $this->hasMany(QuantityCertificateItem::class, 'certificate_id');
    }
}
