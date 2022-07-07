<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BusinessTempClosure extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable, WorkflowTrait;

    protected $guarded = [];

    public function business() {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'submitted_by');
    }

    public function rejected() {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function scopeOpen($query)
    {
        return $query->where('opening_date', '<=', date('Y-m-d'));
    }

}
