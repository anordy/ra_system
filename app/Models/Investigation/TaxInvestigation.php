<?php

namespace App\Models\Investigation;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\FinancialYear;
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

    public function assessments()
    {
        return $this->morphMany(TaxAssessment::class, 'assessment');
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

    public function InvestigationTaxType()
    {
        return $this->taxTypes->map(function ($type) {
            return [
                'id' => $type->id,
                'name' => $type->name
            ];
        });
    }

    public static function generateNewCaseNumber()
    {
        //get the current financial year
        $currentFinancialYear = FinancialYear::where('code', '=', date('Y'))->select('name')->first()->name;

        // Get the last case number for the current financial year
        $lastInvestigation = self::where('case_number', 'like', "TI-{$currentFinancialYear}-%")
            ->orderBy('case_number', 'desc')
            ->first();

        if ($lastInvestigation) {
            $lastCaseNumber = (int) substr($lastInvestigation->case_number, -2);
            $newCaseNumber = str_pad($lastCaseNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            $newCaseNumber = '01';
        }

        return "TI-{$currentFinancialYear}-{$newCaseNumber}";
    }
}
