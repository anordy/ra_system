<?php

namespace App\Models\Debts;

use App\Models\TaxType;
use App\Models\Business;
use App\Models\FinancialMonth;
use App\Models\BusinessLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Debt extends Model
{
    use HasFactory;

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

    public function financialMonth(){
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id');
    }

    public function debtType(){
        return $this->morphTo();
    }
    
}
