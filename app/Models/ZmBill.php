<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZmBill extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bill_items(){
        return $this->hasMany(ZmBillItem::class, 'zm_bill_id');
    }

    public function bill_payments(){
        return $this->hasMany(ZmPayment::class, 'zm_bill_id');
    }

    public function paidAmount(){
        return $this->bill_payments->sum('paid_amount');
    }

	public function user()
	{
		return $this->morphTo();
	}
    

}
