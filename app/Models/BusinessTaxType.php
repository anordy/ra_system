<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessTaxType extends Model
{
    use HasFactory;

    protected $table = 'business_tax_type';

    public function taxTypeChanges(){
        return $this->hasMany(BusinessTaxTypeChange::class);
    }
}
