<?php

namespace App\Models\PropertyTax;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTaxHotelStar extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    }

}
