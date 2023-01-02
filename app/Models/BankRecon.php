<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankRecon extends Model
{
    use HasFactory;

    public $casts = [
        'transaction_date' => 'datetime',
        'actual_transaction_date' => 'datetime',
        'value_date' => 'datetime'
    ];

    protected $guarded = [];

    public function bill(){
        return $this->belongsTo(ZmBill::class, 'control_no', 'control_number');
    }
}
