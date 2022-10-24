<?php

namespace App\Models\Disputes;

use App\Models\Verification\TaxVerificationAssessment;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Objection extends Model implements Auditable
{
    use HasFactory ,WorkflowTrait, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

        public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

       public function bill()
    {
        return $this->morphOne(ZmBill::class, 'billable');
    }
        public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }

      public function taxVerificationAssesment()
    {
        return $this->belongsTo(TaxVerificationAssessment::class,'assesment_id');
    }


}
