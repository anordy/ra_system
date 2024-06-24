<?php

namespace App\Models\Returns\HotelReturns;

use App\Models\ZmBill;
use App\Models\TaxType;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Models\Debts\Debt;
use App\Models\FinancialYear;
use App\Models\FinancialMonth;
use App\Models\BusinessLocation;
use App\Models\Returns\TaxReturn;
use App\Models\WithheldCertificate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use App\Models\Verification\TaxVerification;
use App\Models\WithheldCertificateAttachment;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\HotelReturns\AirbnbAttachment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Returns\HotelReturns\HotelReturnPenalty;
use App\Models\Returns\HotelReturns\HotelLevyAttachment;
use App\Models\Returns\HotelReturns\TourOperatorAttachment;
use App\Models\Returns\HotelReturns\RestaurantLevyAttachment;

class HotelReturn extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $guarded = [];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function verification(){
        return $this->morphOne(TaxVerification::class, 'tax_return');
    }

    public function items(){
        return $this->hasMany(HotelReturnItem::class, 'return_id');
    }

    public function debt(){
        return $this->morphOne(Debt::class, 'debt');
    }

    public function configReturns(){
        return $this->hasMany(HotelReturnItem::class, 'return_id');
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

    public function penalties(){
        return $this->hasMany(HotelReturnPenalty::class, 'return_id');
    }

    public function tax_return(){
        return $this->morphOne(TaxReturn::class, 'return')->withTrashed();
    }

    public function airBnbAttachment(){
        return $this->hasMany(AirbnbAttachment::class, 'hotel_return_id');
    }

    public function hotelLevyAttachment(){
        return $this->hasMany(HotelLevyAttachment::class, 'hotel_return_id');
    }

    public function tourOperatorAttachment(){
        return $this->hasMany(TourOperatorAttachment::class, 'hotel_return_id');
    }

    public function restaurantAttachment(){
        return $this->hasMany(RestaurantLevyAttachment::class, 'hotel_return_id');
    }

    public function withheldCertificates(){
        return $this->morphMany(WithheldCertificateAttachment::class, 'return');
    }

    public function withheld(){
        return $this->morphMany(WithheldCertificate::class, 'return');
    }

}
