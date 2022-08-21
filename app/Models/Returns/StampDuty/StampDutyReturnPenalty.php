<?php

namespace App\Models\Returns\StampDuty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampDutyReturnPenalty extends Model
{
    use HasFactory;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
