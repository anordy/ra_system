<?php

namespace App\Models\Investigation;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxInvestigation extends Model
{
    use HasFactory, WorkflowTrait;

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
    
    public function taxInvestigationLocations(){
        return $this->hasMany(TaxInvestigationLocation::class);
    }

    public function taxInvestigationLocationNames(){
        $locations = null;
        foreach ($this->taxInvestigationLocations as $key => $taxInvestigationLocation) {
            if($key!=0){
                $locations .= ', '; 
            }
            $locations .= $taxInvestigationLocation->businessLocation->name . ' ( '. $taxInvestigationLocation->businessLocation->zin .' )' ;
            
        }
        return $locations;
    }

    public function taxInvestigationTaxTypes(){
        return $this->hasMany(TaxInvestigationTaxType::class);
    }


    public function taxInvestigationTaxTypeNames(){
        $taxType = null;
        foreach ($this->taxInvestigationTaxTypes as $key => $taxInvestigationTaxType) {
            if($key!=0){
                $taxType .= ', '; 
            }
            $taxType .= $taxInvestigationTaxType->taxType->code;
            
        }
        return $taxType;
    }
}
