<?php

namespace App\Models\TaxAudit;

use App\Models\TaxType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxAuditTaxType extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function taxAudit(){
        return $this->belongsTo(TaxAudit::class, 'tax_audit_id');
    }

    public function taxType(){
        return $this->belongsTo(TaxType::class, 'business_tax_type_id');
    }
}
