<?php

namespace App\Models\Returns\Petroleum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetroleumConfig extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
