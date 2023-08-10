<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalBusinessUpdate extends Model
{
    use HasFactory, WorkflowTrait;

    protected $guarded = [];

    public function business() {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function location() {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function staff() {
        return $this->belongsTo(User::class, 'triggered_by');
    }
}
