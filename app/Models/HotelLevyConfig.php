<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelLevyConfig extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    public $guarded = [];

    public function taxtype() {
        return $this->belongsTo(TaxType::class, 'taxtype_id');
    }
}
