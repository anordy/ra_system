<?php

namespace App\Models\Debts;

use App\Models\ZmBill;
use App\Models\TaxType;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Traits\WorkflowTrait;
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
        return $this->belongsTo(BusinessLocation::class, 'location_id');
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
}
