<?php

namespace App\Models\Returns\BFO;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BFOReturnItems extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'bfo_return_items';

    public function config() {
        return $this->belongsTo(BFOConfig::class, 'config_id');
    }

    public function return() {
        return $this->belongsTo(BFOReturn::class, 'return_id');
    }
}
