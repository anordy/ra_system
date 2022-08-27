<?php

namespace App\Models\Returns\ExciseDuty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MnoPenalty extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
    protected $table = 'mno_penalties';
    
    public static function getTableName()
    {
        return with(new static)->getTable();
    }
}
