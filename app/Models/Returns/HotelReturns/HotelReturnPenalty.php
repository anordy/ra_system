<?php

namespace App\Models\Returns\HotelReturns;

use Illuminate\Database\Eloquent\Model;
use App\Models\Returns\HotelReturns\HotelReturn;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HotelReturnPenalty extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function return(){
        return $this->belongsTo(HotelReturn::class);
    }
}
