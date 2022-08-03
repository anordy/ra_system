<?php

namespace App\Models\Returns\HotelReturns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelReturnItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function config() {
        return $this->belongsTo(HotelReturnConfig::class, 'config_id');
    }

    public function return() {
        return $this->belongsTo(HotelReturn::class, 'return_id');
    }

}
