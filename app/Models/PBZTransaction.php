<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PBZTransaction extends Model
{
    protected $table = 'pbz_transactions';

    protected $guarded = [];

    protected $casts = [
        'transaction_time' => 'datetime'
    ];

    public function bill(){
        return $this->belongsTo(ZmBill::class, 'control_number', 'control_number');
    }
}
