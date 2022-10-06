<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use App\Models\BusinessLocation;
use App\Models\TaxAudit\TaxAudit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessDeregistration extends Model
{
    use HasFactory, WorkflowTrait;

    protected $guarded = [];

    public function business() {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'submitted_by');
    }

    public function location() {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function headquarters() {
        return $this->belongsTo(BusinessLocation::class, 'new_headquarter_id');
    }

    public function audit() {
        return $this->belongsTo(TaxAudit::class, 'tax_audit_id');
    }
}
