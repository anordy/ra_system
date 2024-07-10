<?php

namespace App\Models\TaxpayerLedger;

use App\Models\ZmBill;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxpayerLedgerPayment extends Model
{
    use HasFactory, WorkflowTrait;

    protected $guarded = [];

    public function latestBill(){
        return $this->morphOne(ZmBill::class, 'billable')->latest();
    }

    public function bills(){
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function items() {
        return $this->hasMany(TaxpayerLedgerPaymentItem::class, 'payment_id');
    }


}
