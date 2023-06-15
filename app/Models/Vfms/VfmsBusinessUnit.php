<?php

namespace App\Models\Vfms;

use App\Models\Business;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VfmsBusinessUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'zidras_tax_type_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

}
