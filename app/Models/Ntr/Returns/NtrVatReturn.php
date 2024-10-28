<?php

namespace App\Models\Ntr\Returns;

use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Ntr\NtrBusiness;
use App\Models\Taxpayer;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NtrVatReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ntr_electronic_vat_returns';

    protected $guarded = [];

    public function business() {
        return $this->belongsTo(NtrBusiness::class, 'business_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'filed_by_id');
    }

    public function month() {
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id');
    }

    public function year() {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function items(){
        return $this->hasMany(NtrVatReturnItems::class, 'return_id');
    }

    public function penalties(){
        return $this->hasMany(NtrVatReturnPenalty::class, 'return_id');
    }

    public function latestBill(){
        return $this->morphOne(ZmBill::class, 'billable')->latest();
    }

    public function cancellation() {
        return $this->hasOne(NtrVatReturnCancellation::class, 'return_id');
    }

    public function attachments(){
        return $this->hasMany(NtrVatReturnAttachment::class, 'return_id');
    }
}
