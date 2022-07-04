<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZmBill extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function zm_bill_items(){
        return $this->hasMany(ZmBill::class, 'bill_id');
    }

    public function zm_bill_payments(){
        return $this->hasMany(ZmBillPayment::class, 'bill_id');
    }

    public function paid_amount(){
        return $this->zm_bill_payments()->sum('paid_amount');
    }
}
