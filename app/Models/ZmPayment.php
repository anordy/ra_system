<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZmPayment extends Model
{
    use HasFactory;

    protected $casts = [
        'trx_time' => 'datetime'
    ];

    public function bill(){
        return $this->belongsTo(ZmBill::class, 'zm_bill_id');
    }
}
