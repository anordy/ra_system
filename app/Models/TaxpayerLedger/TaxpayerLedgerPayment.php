<?php

namespace App\Models\TaxpayerLedger;

use App\Models\BusinessLocation;
use App\Models\Taxpayer;
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

    public function location() {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }

    public function getLedgerAttribute() {
        $ids = json_decode($this->ledger_ids);
        if (isset($ids[0])) {
            return TaxpayerLedger::find($ids[0]);
        }
        return null;
    }


}
