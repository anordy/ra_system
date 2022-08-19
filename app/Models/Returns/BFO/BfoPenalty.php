<?php

namespace App\Models\Returns\BFO;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BfoPenalty extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'bfo_penalties';

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function BfoReturn(){
        return $this->belongsTo(BfoReturn::class);
    }
}
