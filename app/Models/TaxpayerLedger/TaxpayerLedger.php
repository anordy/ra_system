<?php

namespace App\Models\TaxpayerLedger;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\FinancialMonth;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Models\ZmPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxpayerLedger extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function source()
    {
        return $this->morphTo();
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

    public function financialMonth() {
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id');
    }

    public function payment()
    {
        return $this->belongsTo(ZmPayment::class, 'zm_payment_id');
    }
}
