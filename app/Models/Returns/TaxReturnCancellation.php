<?php

namespace App\Models\Returns;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\TaxType;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxReturnCancellation extends Model
{
    use HasFactory, WorkflowTrait;

    protected $guarded = [];

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function location(){
        return $this->belongsTo(BusinessLocation::class);
    }

    public function taxType(){
        return $this->belongsTo(TaxType::class);
    }

    public function trashedReturn(){
        return $this->trashedTaxReturn()->with(['return' => function ($query) {
            $query->withTrashed();
        }]);
    }

    public function trashedTaxReturn(){
        return $this->belongsTo(TaxReturn::class, 'tax_return_id')->withTrashed();
    }

    public function return(){
        return $this->morphTo();
    }

    public function taxReturn(){
        return $this->belongsTo(TaxReturn::class, 'tax_return_id');
    }

    public function files(){
        return $this->hasMany(TaxReturnCancellationFile::class);
    }

    public function requestedBy(){
        return $this->morphTo();
    }
}
