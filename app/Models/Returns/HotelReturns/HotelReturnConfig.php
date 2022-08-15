<?php

namespace App\Models\Returns\HotelReturns;

use App\Models\TaxType;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelReturnConfig extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function taxtype() {
        return $this->belongsTo(TaxType::class, 'taxtype_id');
    }

}
