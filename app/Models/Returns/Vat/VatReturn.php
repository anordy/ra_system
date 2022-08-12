<?php

namespace App\Models\Returns\Vat;

use App\Models\Business;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatReturn extends Model
{
    use HasFactory;
    protected $table = 'vat_returns';
    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id', 'id');
    }

    public function items(){
        return $this->hasMany(VatReturnItem::class, 'vat_return_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'filled_id');
    }

    public function financialYear() {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id','id');
    }

    public function financialMonth() {
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id','id');
    }

    public function bill(){
        return $this->morphOne(ZmBill::class, 'billable');
    }
//    public function bills(){
//        return $this->morphMany(ZmBill::class, 'billable');
//    }

    public function penalties(){
        return $this->hasMany(VatReturnPenalty::class, 'return_id');
    }


}
