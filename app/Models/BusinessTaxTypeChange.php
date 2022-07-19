<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessTaxTypeChange extends Model
{
    use HasFactory, WorkflowTrait;

    protected $guarded = [];

    public function taxType() {
        return $this->belongsTo(BusinessTaxType::class, 'business_tax_type_id');
    }
}
