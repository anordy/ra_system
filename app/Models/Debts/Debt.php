<?php

namespace App\Models\Debts;

use App\Models\Extension\ExtensionRequest;
use App\Models\Installment\Installment;
use App\Models\Installment\InstallmentRequest;
use App\Models\ZmBill;
use App\Models\TaxType;
use App\Models\Business;
use App\Traits\WorkflowTrait;
use App\Models\BusinessLocation;
use App\Models\Debts\DebtPenalty;
use App\Models\Debts\RecoveryMeasure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Debt extends Model
{
    protected $table = 'debts';
    
    use HasFactory, WorkflowTrait;

    protected $guarded = [];
    
    public function business() {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function location() {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }

    public function taxtype() {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

    public function debtType(){
        return $this->morphTo();
    }

    public function getBillAttribute(){
        return $this->morphMany(ZmBill::class, 'billable')->latest()->first();
    }
    
    public function debtPenalties(){
        return $this->hasMany(DebtPenalty::class);
    }

    public function recoveryMeasures() {
        return $this->hasMany(RecoveryMeasure::class);
    }

    public function bills(){
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function debt(){
        return $this->morphTo();
    }

    public function extensionRequest(){
        return $this->hasOne(ExtensionRequest::class);
    }

    public function installmentRequest(){
        return $this->hasOne(InstallmentRequest::class);
    }

    public function installment(){
        return $this->hasOne(Installment::class);
    }
}
