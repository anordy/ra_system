<?php

namespace App\Models\Returns;

use App\Models\ZmBill;
use App\Models\TaxType;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Models\FinancialMonth;
use App\Models\BusinessLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxReturn extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'curr_filing_due_date' => 'datetime',
    ];


    public function taxtype(){
        return $this->belongsTo(TaxType::class,'tax_type_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'filed_by_id');
    }

    public function location(){
        return $this->belongsTo(BusinessLocation::class,'location_id');
    }

    public function business() {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function financialMonth(){
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id');
    }

    public function return(){
        return $this->morphTo();
    }

    public function bills(){
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function bill(){
        return $this->morphOne(ZmBill::class, 'billable');
    }
}
