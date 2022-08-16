<?php

namespace App\Models;

use App\Models\Verification\TaxVerificationAssessment;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaiverObjection extends Model
{
    use HasFactory;

    use HasFactory, WorkflowTrait;

    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function waiverStatus()
    {
        return $this->hasOne(WaiverStatus::class);
    }

    public function taxVerificationAssesment()
    {
        return $this->belongsTo(TaxVerificationAssessment::class, 'assesment_id');
    }

}
