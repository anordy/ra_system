<?php

namespace App\Models;

use App\Models\TaxType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessTaxType extends Model
{
    use HasFactory;

    protected $table = 'business_tax_type';

    protected $guarded = [];

    public function taxType() {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

}
