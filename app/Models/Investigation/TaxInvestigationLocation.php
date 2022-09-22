<?php

namespace App\Models\Investigation;

use App\Models\BusinessLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxInvestigationLocation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function taxInvestigation(){
        return $this->belongsTo(TaxInvestigation::class, 'tax_investigation_id');
    }

    public function businessLocation(){
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }
}
