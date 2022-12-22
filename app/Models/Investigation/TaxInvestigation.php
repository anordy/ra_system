<?php

namespace App\Models\Investigation;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TaxInvestigation extends Model implements Auditable
{
    use HasFactory, WorkflowTrait, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function taxType()
    {
        return $this->belongsTo(TaxType::class);
    }

    public function taxReturn()
    {
        return $this->morphTo();
    }

    public function createdBy()
    {
        return $this->morphTo();
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function assessment()
    {
        return $this->morphOne(TaxAssessment::class, 'assessment');
    }

    public function officers()
    {
        return $this->hasMany(TaxInvestigationOfficer::class, 'investigation_id', 'id');
    }

    public function taxInvestigationLocations()
    {
        return $this->hasMany(TaxInvestigationLocation::class);
    }

    public function businessLocations()
    {
        return $this->hasManyThrough(BusinessLocation::class, TaxInvestigationLocation::class, 'tax_investigation_id', 'id', 'id', 'business_location_id');
    }

    public function taxInvestigationLocationNames()
    {
        return $this->businessLocations->map(fn ($location) => $location->name . '(' . $location->zin . ')')->implode(',', 'name');
    }

    public function taxInvestigationTaxTypes()
    {
        return $this->hasMany(TaxInvestigationTaxType::class);
    }


    public function taxTypes()
    {
        return $this->hasManyThrough(TaxType::class, TaxInvestigationTaxType::class, 'tax_investigation_id', 'id', 'id', 'business_tax_type_id');
    }

    public function taxInvestigationTaxTypeNames()
    {
        return $this->taxTypes->map(fn ($type) => $type->name)->implode(',', 'name');
    }
}
