<?php

namespace App\Models\Returns\LumpSum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LumpSumPenalties extends Model
{
    use HasFactory;
    public $table  = 'lump_sum_penalties';

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function return(){
        return $this->belongsTo(LumpSumReturn::class);
    }
}
