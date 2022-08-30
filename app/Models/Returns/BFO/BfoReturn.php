<?php

namespace App\Models\Returns\BFO;

use App\Models\ZmBill;
use App\Models\TaxType;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Models\Debts\Debt;
use App\Models\FinancialYear;
use App\Models\FinancialMonth;
use App\Models\BusinessLocation;
use App\Models\Returns\BFO\BfoPenalty;
use Illuminate\Database\Eloquent\Model;
use App\Models\Returns\BFO\BfoReturnItems;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BfoReturn extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = 'bfo_returns';

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function items(){
        return $this->hasMany(BfoReturnItems::class, 'return_id');
    }

    public function debt(){
        return $this->morphOne(Debt::class, 'debt');
    }

    public function configReturns(){
        return $this->hasMany(BfoReturnItems::class, 'return_id');
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

    public function penalties(){
        return $this->hasMany(BfoPenalty::class, 'return_id');
    }

    public function bills(){
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function getBillAttribute(){
        return $this->morphMany(ZmBill::class, 'billable')->latest()->first();
    }

    public function payments()
    {
        return $this->bills()->where('status', 'paid');
    }
}
