<?php

namespace App\Models;

use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MvrDeregistration extends Model
{
    use HasFactory, WorkflowTrait;

    protected $guarded = [];

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }

    public function reason(){
        return $this->belongsTo(MvrDeRegistrationReason::class, 'mvr_de_registration_reason_id');
    }

    public function registration(){
        return $this->belongsTo(MvrRegistration::class, 'mvr_registration_id');
    }

    public function latestBill()
    {
        return $this->morphOne(ZmBill::class, 'billable')->latest();
    }

    public function ledger()
    {
        return $this->morphOne(TaxpayerLedger::class, 'source');
    }
}
