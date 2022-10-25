<?php

namespace App\Models\Extension;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Returns\TaxReturn;
use App\Models\TaxType;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ExtensionRequest extends Model implements Auditable
{
    use HasFactory, SoftDeletes, WorkflowTrait, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $casts = [
        'extend_from' => 'datetime',
        'extend_to' => 'datetime'
    ];

    public function extensible(){
        return $this->morphTo();
    }

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function location(){
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function files(){
        return $this->hasMany(ExtensionRequestFile::class);
    }

    public function taxType(){
        return $this->belongsTo(TaxType::class);
    }
}
