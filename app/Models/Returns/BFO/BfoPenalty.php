<?php

namespace App\Models\Returns\BFO;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BfoPenalty extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'bfo_penalties';

    public function BfoReturn(){
        return $this->belongsTo(BfoReturn::class);
    }
}
