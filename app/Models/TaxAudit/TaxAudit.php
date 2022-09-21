<?php

namespace App\Models\TaxAudit;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxAudit extends Model
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
        return $this->hasMany(TaxAuditOfficer::class, 'audit_id', 'id');
    }

    public function taxAuditLocations(){
        return $this->hasMany(TaxAuditLocation::class);
    }

    public function taxAuditLocationNames(){
        $locations = null;
        foreach ($this->taxAuditLocations as $key => $taxAuditLocation) {
            if($key!=0){
                $locations .= ', '; 
            }
            $locations .= $taxAuditLocation->businessLocation->name . ' ( '. $taxAuditLocation->businessLocation->zin .' )' ;
            
        }
        return $locations;
    }

    public function taxAuditTaxTypes(){
        return $this->hasMany(TaxAuditTaxType::class);
    }


    public function taxAuditTaxTypeNames(){
        $taxType = null;
        foreach ($this->taxAuditTaxTypes as $key => $taxAuditTaxType) {
            if($key!=0){
                $taxType .= ', '; 
            }
            $taxType .= $taxAuditTaxType->taxType->code;
            
        }
        return $taxType;
    }
    
}
