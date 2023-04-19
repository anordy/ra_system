<?php

namespace App\Models\Returns\HotelReturns;

use Illuminate\Database\Eloquent\Model;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelReturnItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function config() {
        return $this->belongsTo(HotelReturnConfig::class, 'config_id');
    }

    public function configuration() {
        return $this->hasOne(HotelReturnConfig::class, 'config_id');
    }

    public function return() {
        return $this->belongsTo(HotelReturn::class, 'return_id');
    }

}
