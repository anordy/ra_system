<?php

namespace App\Models\VatReturn;

use App\Models\Business;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatReturn extends Model
{
    use HasFactory;

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'taxtype_code', 'code');
    }
}
