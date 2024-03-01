<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PBZReversal extends Model
{
    protected $table = 'pbz_reversals';

    protected $guarded = [];

    public function bill(){
        return $this->belongsTo(ZmBill::class, 'control_number', 'control_number');
    }
}
