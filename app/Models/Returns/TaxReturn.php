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
use App\Models\Debts\DebtRollback;
use App\Models\Debts\DemandNotice;
use App\Models\Returns\Vat\SubVat;
use App\Models\Debts\RecoveryMeasure;
use App\Models\Installment\Installment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Extension\ExtensionRequest;
use App\Models\Installment\InstallmentRequest;
use App\Services\Verification\PayloadInterface;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxReturn extends Model implements PayloadInterface
{
    use HasFactory, WorkflowTrait;

    protected $guarded = [];

    protected $casts = [
        'filing_due_date' => 'date',
        'payment_due_date' => 'date',
        'curr_filing_due_date' => 'datetime',
        'curr_payment_due_date' => 'datetime',
        'paid_at' => 'datetime'
    ];


    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

    public function subvat()
    {
        return $this->belongsTo(SubVat::class, 'sub_vat_id');
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

    public function secondLatest()
    {
        return $this->morphMany(ZmBill::class, 'billable')->latest();
    }

    public function bill()
    {
        return $this->morphOne(ZmBill::class, 'billable');
    }

    public function latestBill()
    {
        return $this->morphOne(ZmBill::class, 'billable')->latest();
    }


    public function extensionRequest()
    {
        return $this->morphOne(ExtensionRequest::class, 'extensible');
    }

    public function installmentRequest()
    {
        return $this->morphOne(InstallmentRequest::class, 'installable');
    }

    public function installment()
    {
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

    public function waiver()
    {
        return $this->morphOne(DebtWaiver::class, 'debt');
    }

    public function rollback()
    {
        return $this->morphOne(DebtRollback::class, 'debt');
    }

    public function penalties()
    {
        return $this->morphMany(DebtPenalty::class, 'debt');
    }

    public function latestPenalty()
    {
        return $this->morphOne(DebtPenalty::class, 'debt')->latest();
    }

    public function editReturnHistories()
    {
        return $this->hasMany(TaxReturnHistory::class, 'tax_return_id');
    }
    public static function getPayloadColumns(): array
    {
        return [
            'id',
            'business_id',
            'location_id',
            'total_amount', // Debt, Waiver, Rollback
            //'outstanding_amount', // Installment, extension, debt(job)
            'principal',
            'interest', // Debt, Waiver, Rollback
            'penalty', // Debt , Waiver, Rollback
            'currency',
            //'curr_payment_due_date', // Installment, extension, debt(job)
        ];
    }

    public static function getTableName(): string
    {
        return 'tax_returns';
    }

    public function scopePreviousReturns(Builder $query, int $count)
    {
        return $query->where('business_id', $this->business_id)
            ->where('financial_month_id', '<', $this->financial_month_id)
            ->orderBy('financial_month_id', 'desc')
            ->limit($count);
    }

    public function getPreviousReturns(int $count = 2)
    {
        return $this->scopePreviousReturns((new static)->newQuery(), $count)->get();
    }

}
