<?php

namespace App\Models\Debts;

use App\Models\ZmBill;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Models\Debts\Debt;
use App\Models\WaiverStatus;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DebtWaiver extends Model
{
    use HasFactory, WorkflowTrait;

    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function debt()
    {
        return $this->belongsTo(Debt::class, 'debt_id');
    }

    public function waiverStatus()
    {
        return $this->hasOne(WaiverStatus::class);
    }
    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }

    public function bill()
    {
        return $this->morphOne(ZmBill::class, 'billable');
    }
    
}
