<?php

namespace App\Models\Disputes;

use App\Models\TaxAssessments\TaxAssessment;
use App\Models\Verification\TaxVerificationAssessment;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Waiver extends Model implements Auditable
{
    use HasFactory, WorkflowTrait, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function waiverStatus()
    {
        return $this->hasOne(WaiverStatus::class);
    }
    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }

    public function bill()
    {
        return $this->morphOne(ZmBill::class, 'billable');
    }
      public function assessment()
    {
        return $this->belongsTo(TaxAssessment::class,'assesment_id');
    }

}
