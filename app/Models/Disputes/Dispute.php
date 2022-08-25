<?php

namespace App\Models\Disputes;

use App\Models\Business;
use App\Models\Debts\Debt;
use App\Models\Taxpayer;
use App\Models\WaiverStatus;
use App\Models\ZmBill;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    use HasFactory, WorkflowTrait;


    protected $guarded = [];

    
    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function waiverStatus()
    {
        return $this->hasOne(WaiverStatus::class);
    }
    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
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
}
