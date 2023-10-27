<?php

namespace App\Models\PropertyTax;

use App\Enum\PaymentExtensionStatus;
use App\Models\Currency;
use App\Models\FinancialYear;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function property(){
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function year(){
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function bills()
    {
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function bill()
    {
        return $this->morphOne(ZmBill::class, 'billable');
    }

    public function latestBill()
    {
        return $this->morphOne(ZmBill::class, 'billable')->latest();
    }

    public function paymentExtension(){
        return $this->hasMany(PaymentExtension::class, 'property_payment_id');
    }

    public function checkAnyPendingExtensionRequest(){
        return $this->paymentExtension()->where('status', PaymentExtensionStatus::PENDING)->exists();
    }
}
