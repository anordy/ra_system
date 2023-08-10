<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessHotel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function star(){
        return $this->belongsTo(HotelStar::class, 'hotel_star_id');
    }

    public function business(){
        return $this->belongsTo(Business::class, 'business_id');
    }
}
