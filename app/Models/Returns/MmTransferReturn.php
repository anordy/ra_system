<?php

namespace App\Models\Returns;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MmTransferReturn extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = 'mm_transfer_returns';

    public function mmTransferReturnItems(){
        return $this->hasMany(MmTransferReturnItem::class, 'return_id');
    }

    public function configReturns(){
        return $this->hasMany(MmTransferReturnItem::class, 'return_id');
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

    public function mmTransferPenalties(){
        return $this->hasMany(MmTransferPenalty::class, 'return_id');
    }
}
