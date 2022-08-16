<?php

namespace App\Models\TaxAssessments;

use App\Models\TaxType;
use App\Models\Business;
use App\Models\FinancialMonth;
use App\Models\BusinessLocation;
use App\Models\Investigation\TaxInvestigation;
use App\Models\TaxAudit\TaxAudit;
use App\Models\Verification\TaxVerification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxAssessmentHistory extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function taxAssessment()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

    public function financialMonth()
    {
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id');
    }

    public function assessment()
    {
        return $this->morphTo();
    }

    public function scopeVerification($query)
    {
        return $query->where('assessment_type', TaxVerification::class);
    }

    public function scopeAudit($query)
    {
        return $query->where('assessment_type', TaxAudit::class);
    }

    public function scopeInvestigation($query)
    {
        return $query->where('assessment_type', TaxInvestigation::class);
    }
}
