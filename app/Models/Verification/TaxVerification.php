<?php

namespace App\Models\Verification;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\TaxType;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }
}
