<?php

namespace App\Models\Returns\Vat;

use App\Models\Claims\TaxClaim;
use App\Models\Claims\TaxCreditItem;
use App\Models\ZmBill;
use App\Models\TaxType;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Models\Debts\Debt;
use App\Models\FinancialYear;
use App\Models\FinancialMonth;
use App\Models\BusinessLocation;
use App\Models\Returns\TaxReturn;
use App\Models\Returns\Vat\SubVat;
use Illuminate\Database\Eloquent\Model;
use App\Models\Verification\TaxVerification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VatReturn extends Model
{
    use HasFactory;
    protected $table = 'vat_returns';
    protected $guarded = [];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function verification(){
        return $this->morphOne(TaxVerification::class, 'tax_return');
    }

    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id', 'id');
    }

    public function subvat() {
        return $this->belongsTo(SubVat::class, 'sub_vat_id');
    }

    public function items()
    {
        return $this->hasMany(VatReturnItem::class, 'return_id');
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class, 'filed_by_id', 'id');
    }

    public function businessLocation() {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }

    public function financialYear() {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id','id');
    }

    public function financialMonth()
    {
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id', 'id');
    }

    public function debt(){
        return $this->morphOne(Debt::class, 'debt');
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

    public function penalties()
    {
        return $this->hasMany(VatReturnPenalty::class, 'return_id');
    }

    public function suppliers()
    {
        return $this->hasMany(VatReturnSupplierDetail::class, 'vat_return_id');
    }

    public function hotelDetails(){
        return $this->hasMany(VatHotelDetail::class, 'vat_return_id');
    }

    public function zeroRatedDetails(){
        return $this->hasMany(VatZeroRatedSale::class, 'vat_return_id');
    }

    public function cashSales(){
        return $this->hasMany(VatCashSales::class, 'vat_return_id');
    }


    public function claimable(){
        return $this->morphOne(TaxClaim::class, 'old_return_id');
    }

    public function credits(){
        return $this->hasMany(TaxCreditItem::class, 'return_id');
    }

    public function tax_return(){
        return $this->morphOne(TaxReturn::class, 'return');
    }
}
