<?php

namespace App\Models\Installment;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\TaxType;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstallmentRequest extends Model
{
    use HasFactory, SoftDeletes, WorkflowTrait;

    protected $guarded = [];

    protected $casts = [
        'installment_from' => 'datetime',
        'installment_to' => 'datetime',
    ];

    public function installable(){
        return $this->morphTo();
    }

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function location(){
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function files(){
        return $this->hasMany(InstallmentRequestFile::class);
    }

    public function taxType(){
        return $this->belongsTo(TaxType::class);
    }

    public function createdBy(){
        return $this->morphTo();
    }
}
