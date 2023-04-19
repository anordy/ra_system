<?php

namespace App\Models\Returns\BFO;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BfoPenalty extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = 'bfo_penalties';

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function return(){
        return $this->belongsTo(BfoReturn::class);
    }
}
