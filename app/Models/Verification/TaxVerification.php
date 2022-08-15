<?php

namespace App\Models\Verification;

use App\Models\Business;
use App\Models\TaxType;
use App\Traits\WorkflowTrait;
use App\Models\BusinessLocation;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaxAssessments\TaxAssessment;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxVerification extends Model
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

    public function businesses()
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
        return $this->hasMany(TaxVerificationOfficer::class, 'verification_id', 'id');
    }
    
}
