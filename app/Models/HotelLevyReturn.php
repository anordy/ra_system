<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HotelLevyReturn extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    public $guarded = [];

    public function hotelLevyConfigReturns(){
        return $this->hasMany(HotelConfigReturn::class, 'return_id');
    }

    public function business() {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function businessLocation() {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }

    public function taxpayer() {
        return $this->belongsTo(User::class, 'filled_id');
    }

}
