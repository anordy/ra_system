<?php

namespace App\Models\Investigation;

use App\Models\TaxType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxInvestigationTaxType extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function taxInvestigation(){
        return $this->belongsTo(TaxInvestigation::class, 'tax_investigation_id');
    }

    public function taxType(){
        return $this->belongsTo(TaxType::class, 'business_tax_type_id');
    }
}
