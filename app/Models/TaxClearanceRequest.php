<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxClearanceRequest extends Model
{
    use HasFactory, SoftDeletes,WorkflowTrait;

    protected $table = 'tax_clearance_requests';
	protected $guarded = [];

    public function business(){
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function businessLocation(){
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }
}
