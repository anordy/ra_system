<?php

namespace App\Models\Returns\Port;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortReturnPenalty extends Model
{
    use HasFactory;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    protected $guarded = [];

    public function return(){
        return $this->belongsTo(PortReturn::class);
    }

}
