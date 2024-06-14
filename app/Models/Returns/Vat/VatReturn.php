<?php

namespace App\Models\Returns\Vat;

use App\Models\Claims\TaxClaim;
use App\Models\Claims\TaxCreditItem;
use App\Models\TaxRefund\TaxRefundItem;
use App\Models\Tra\ExitedGood;
use App\Models\WithheldCertificate;
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
use Illuminate\Database\Eloquent\SoftDeletes;

class VatReturn extends Model
{
    use HasFactory, SoftDeletes;

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

    public function vatWithheld(){
        return $this->hasMany(VatWithheldAttachment::class, 'vat_return_id');
    }

    public function withheldDetails(){
        return $this->morphMany(WithheldCertificate::class, 'return');
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

    public function specialRelief(){
        return $this->hasMany(VatSpecialRelief::class, 'vat_return_id');
    }

    public function exemptSupplies(){
        return $this->hasMany(VatExemptSupply::class, 'vat_return_id');
    }

    public function importPurchases(){
        return $this->hasMany(ExitedGood::class, 'vat_return_id');
    }

    public function standardPurchases(){
        return $this->hasMany(TaxRefundItem::class, 'vat_return_id');
    }
}
