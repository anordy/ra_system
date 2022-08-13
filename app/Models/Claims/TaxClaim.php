<?php

namespace App\Models\Claims;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\FinancialMonth;
use App\Models\Taxpayer;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxClaim extends Model
{
    use HasFactory, SoftDeletes, WorkflowTrait;

    protected $guarded = [];

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function financialMonth(){
        return $this->belongsTo(FinancialMonth::class);
    }

    public function oldReturn(){
        return $this->morphTo();
    }

    public function newReturn(){
        return $this->morphTo();
    }

    public function createdBy()
    {
        return $this->morphTo();
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function assessment()
    {
        return $this->belongsTo(TaxClaimAssessment::class, 'id', 'claim_id');
    }

    public function officers()
    {
        return $this->hasMany(TaxClaimOfficer::class, 'claim_id', 'id');
    }

    public function credit(){
        return $this->hasOne(TaxCredit::class, 'claim_id');
    }

    public function taxpayer(){
        return $this->morphTo('created_by');
    }
}
