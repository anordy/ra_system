<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelConfigReturn extends Model
{
    use HasFactory;

    private $guarded = [];

    public function config() {
        return $this->belongsTo(HotelLevyConfig::class, 'config_id');
    }

    public function return() {
        return $this->belongsTo(HotelLevyReturn::class, 'return_id');
    }

}
