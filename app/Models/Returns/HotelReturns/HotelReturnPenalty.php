<?php

namespace App\Models\Returns\HotelReturns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelReturnPenalty extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public static function getTableName()
    {
        return with(new static)->getTable();
    }

}
