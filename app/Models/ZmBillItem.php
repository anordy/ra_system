<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZmBillItem extends Model
{
    use HasFactory;
	protected $guarded = [];

    protected $with = ['payment'];

    public function payment()
    {
        return $this->belongsTo(ZmBill::class, 'zm_bill_id');
    }

    public function bill()
    {
        return $this->belongsTo(ZmBill::class, 'zm_bill_id');
    }

    public function taxType(){
        return $this->belongsTo(TaxType::class);
    }
}
