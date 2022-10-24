<?php

namespace App\Models;

use App\Models\TaxType;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class BusinessTaxTypeChange extends Model implements Auditable
{
    use HasFactory, WorkflowTrait, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function business() {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }

    public function fromTax() {
        return $this->belongsTo(TaxType::class, 'from_tax_type_id');
    }

    public function toTax() {
        return $this->belongsTo(TaxType::class, 'to_tax_type_id');
    }
}
