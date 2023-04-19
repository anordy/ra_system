<?php

namespace App\Models\Returns\StampDuty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StampDutyReturnPenalty extends Model
{
    use HasFactory, SoftDeletes;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function return(){
        return $this->belongsTo(StampDutyReturn::class);
    }
}
