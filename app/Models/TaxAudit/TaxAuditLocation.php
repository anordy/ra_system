<?php

namespace App\Models\TaxAudit;

use App\Models\BusinessLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxAuditLocation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function taxAudit(){
        return $this->belongsTo(TaxAudit::class, 'tax_audit_id');
    }

    public function businessLocation(){
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }
}
