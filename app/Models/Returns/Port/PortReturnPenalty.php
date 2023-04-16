<?php

namespace App\Models\Returns\Port;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortReturnPenalty extends Model
{
    use HasFactory, SoftDeletes;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    protected $guarded = [];

    public function return(){
        return $this->belongsTo(PortReturn::class);
    }

}
