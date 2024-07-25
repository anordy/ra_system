<?php

namespace App\Models\TaxpayerLedger;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxpayerLedgerBreakdown extends Model
{
    use HasFactory;

    public function payment() {
        return $this->belongsTo(TaxpayerLedgerPayment::class, 'ledger_payment_id');
    }

    public function paymentItem() {
        return $this->belongsTo(TaxpayerLedgerPaymentItem::class, 'ledger_payment_item_id');
    }
}
