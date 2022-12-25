<?php

namespace App\Models\Returns;

use App\Models\ZmBill;
use App\Models\TaxType;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Models\FinancialMonth;
use App\Models\BusinessLocation;
use App\Models\Debts\DebtWaiver;
use App\Models\Debts\DebtPenalty;
use App\Models\Debts\DemandNotice;
use App\Models\Debts\RecoveryMeasure;
use App\Models\Installment\Installment;
use Illuminate\Database\Eloquent\Model;
use App\Models\Extension\ExtensionRequest;
use App\Models\Installment\InstallmentRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxReturn extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'filing_due_date' => 'date',
        'payment_due_date' => 'date',
        'curr_filing_due_date' => 'datetime',
        'curr_payment_due_date' => 'datetime'
    ];


    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class, 'filed_by_id');
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function financialMonth()
    {
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id');
    }

    public function return()
    {
        return $this->morphTo();
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


    public function extensionRequest(){
        return $this->morphOne(ExtensionRequest::class, 'extensible');
    }

    public function installmentRequest(){
        return $this->morphOne(InstallmentRequest::class, 'installable');
    }

    public function installment(){
        return $this->morphOne(Installment::class, 'installable');
    }


    public function recoveryMeasure()
    {
        return $this->morphOne(RecoveryMeasure::class, 'debt');
    }

    public function demandNotices()
    {
        return $this->morphMany(DemandNotice::class, 'debt');
    }

    public function waiver(){
        return $this->morphOne(DebtWaiver::class, 'debt');
    }

    public function penalties(){
        return $this->morphMany(DebtPenalty::class, 'debt');
    }
}
