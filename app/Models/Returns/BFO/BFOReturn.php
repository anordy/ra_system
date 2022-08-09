<?php

namespace App\Models\Returns\BFO;

use App\Models\Business;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Returns\Vat\VatReturnItem;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BFOReturn extends Model
{
    use HasFactory;
    protected $table = 'bfo_returns';

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id', 'id');
    }

    public function items(){
        return $this->hasMany(BFOReturnItems::class, 'bfo_return_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'filled_id');
    }

    public function financialYear() {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id','id');
    }

    public function financialMonth() {
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id','id');
    }

    public function bill(){
        return $this->morphOne(ZmBill::class, 'billable');
    }
}
