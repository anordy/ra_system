<?php

namespace App\Models\Returns;

use App\Models\ZmBill;
use App\Models\TaxType;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Models\FinancialYear;
use App\Models\FinancialMonth;
use App\Models\BusinessLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmTransactionReturn extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = 'em_transaction_returns';

    public function emTransactionReturnItems(){
        return $this->hasMany(EmTransactionReturnItem::class, 'return_id');
    }

    public function configReturns(){
        return $this->hasMany(EmTransactionReturnItem::class, 'return_id');
    }

    public function business() {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function businessLocation() {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'filed_by_id');
    }

    public function taxtype() {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

    public function financialYear() {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function financialMonth(){
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id');
    }

    public function bill(){
        return $this->morphOne(ZmBill::class, 'billable');
    }

    public function bills()
    {
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function payments()
    {
        return $this->bills()->where('status', 'paid');
    }

    public function emTransactionPenalties(){
        return $this->hasMany(EmTransactionPenalty::class, 'return_id');
    }
}
