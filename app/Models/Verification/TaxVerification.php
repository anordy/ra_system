<?php

namespace App\Models\Verification;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\RiskIndicator;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TaxVerification extends Model implements Auditable
{
    use HasFactory, WorkflowTrait, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
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

    public function riskIndicators()
    {
        return $this->belongsToMany(RiskIndicator::class, 'tax_verification_risk_indicator', 'verification_id', 'risk_indicator_id');
    }

    public function files()
    {
        return $this->hasMany(TaxVerificationFile::class, 'tax_verification_id', 'id');
    }
}
