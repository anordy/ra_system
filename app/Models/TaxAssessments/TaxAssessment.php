<?php

namespace App\Models\TaxAssessments;

use App\Models\ZmBill;
use App\Models\TaxType;
use App\Models\Business;
use App\Models\FinancialMonth;
use App\Models\BusinessLocation;
use App\Models\Disputes\Dispute;
use App\Models\Debts\DebtPenalty;
use App\Models\TaxAudit\TaxAudit;
use App\Models\Debts\DemandNotice;
use Illuminate\Database\Eloquent\Model;
use App\Models\Verification\TaxVerification;
use App\Models\Investigation\TaxInvestigation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;


class TaxAssessment extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    protected $casts = [
        'curr_payment_due_date' => 'datetime'
    ];

    public function dispute(){
        return $this->belongsTo(Dispute::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

    public function financialMonth()
    {
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id');
    }

    public function assessment()
    {
        return $this->morphTo();
    }

    public function scopeVerification($query)
    {
        return $query->where('assessment_type', TaxVerification::class);
    }

    public function scopeAudit($query)
    {
        return $query->where('assessment_type', TaxAudit::class);
    }

    public function scopeInvestigation($query)
    {
        return $query->where('assessment_type', TaxInvestigation::class);
    }

    public function bills(){
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function latestBill()
    {
        return $this->morphOne(ZmBill::class, 'billable')->latest();
    }

    public function getBillAttribute(){
        return $this->morphMany(ZmBill::class, 'billable')->latest()->first();
    }

    public function payments()
    {
        return $this->bills()->where('status', 'paid');
    }

    public function penalties(){
        return $this->morphMany(DebtPenalty::class, 'debt');
    }

    public function demandNotices()
    {
        return $this->morphMany(DemandNotice::class, 'debt');
    }
}
