<?php

namespace App\Models\Returns\HotelReturns;

use Illuminate\Database\Eloquent\Model;
use App\Models\Returns\HotelReturns\HotelReturn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelReturnPenalty extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    
    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function return(){
        return $this->belongsTo(HotelReturn::class);
    }
}
