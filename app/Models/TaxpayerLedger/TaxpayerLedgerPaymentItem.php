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

    public function payment() {
        return $this->belongsTo(TaxpayerLedgerPayment::class, 'payment_id');
    }

    public function breakdown() {
        return $this->belongsTo(TaxpayerLedgerBreakdown::class, 'id', 'ledger_payment_item_id')
            ->select(['principal', 'interest', 'penalty', 'airport_safety_fee', 'infrastructure', 'airport_service_charge', 'seaport_transport_charge', 'infrastructure_znz_znz', 'infrastructure_znz_tz', 'rdf_fee', 'road_license_fee']);
    }

}
