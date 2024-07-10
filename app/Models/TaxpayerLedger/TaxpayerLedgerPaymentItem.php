<?php

namespace App\Models\TaxpayerLedger;

use App\Models\TaxType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxpayerLedgerPaymentItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function taxtype() {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

    public function ledger() {
        return $this->belongsTo(TaxpayerLedger::class, 'ledger_id');
    }

}
