<?php

namespace App\Models\Returns\Petroleum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetroleumPenalty extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'petroleum_penalties';

    public function petroleumReturn(){
        return $this->belongsTo(PetroleumReturn::class);
    }
}
