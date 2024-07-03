<?php

namespace App\Models\TaxAudit;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxType;
use App\Traits\WorkflowTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class TaxAudit extends Model implements Auditable
{
    use HasFactory, WorkflowTrait, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $casts = [
        'period_from' => 'datetime',
        'period_to' => 'datetime',
    ];

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

    public function assessments()
    {
        return $this->morphMany(TaxAssessment::class, 'assessment');
    }

    public function officers()
    {
        return $this->hasMany(TaxAuditOfficer::class, 'audit_id', 'id');
    }

    public function taxAuditLocations()
    {
        return $this->hasMany(TaxAuditLocation::class);
    }

    public function businessLocations()
    {
        return $this->hasManyThrough(BusinessLocation::class, TaxAuditLocation::class, 'tax_audit_id', 'id', 'id', 'business_location_id');
    }

    public function taxAuditLocationNames()
    {
        return $this->businessLocations->map(fn ($location) => $location->name . '(' . $location->zin . ')')->implode(',', 'name');
    }

    public function taxAuditTaxTypes()
    {
        return $this->hasMany(TaxAuditTaxType::class);
    }

    public function taxTypes()
    {
        return $this->hasManyThrough(TaxType::class, TaxAuditTaxType::class, 'tax_audit_id', 'id', 'id', 'business_tax_type_id');
    }

    public function taxAuditTaxType()
    {
        return $this->taxTypes->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name
            ];
        });
    }


    public function taxAuditTaxTypeNames()
    {
        return $this->taxTypes->map(fn ($type) => $type->name)->implode(',', 'name');
    }

    public function periodFrom()
    {
        return !isNullOrEmpty($this->period_from) ? Carbon::create($this->period_from)->format('d-m-Y') : null;
    }

    public function periodTo()
    {
        return !isNullOrEmpty($this->period_to) ? Carbon::create($this->period_to)->format('d-m-Y') : null;
    }

    public function auditingDate()
    {
        return !isNullOrEmpty($this->auditing_date) ? Carbon::create($this->auditing_date)->format('d-m-Y') : null;
    }
}
