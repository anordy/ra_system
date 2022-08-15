<?php

namespace App\Models\Returns\LumpSum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BusinessLocation;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Business;
use App\Models\LumpSumPayment;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Models\ZmBill;

class LumpSumReturn extends Model
{
    use HasFactory;
    public $table         = 'lump_sum_returns';
    protected $fillable   = [];
    protected $guarded    = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function businessLocation()
    {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class, 'filed_by_id');
    }

    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id', 'id');
    }

    public function financialMonth()
    {
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id');
    }

    public function bill()
    {
        return $this->morphOne(ZmBill::class, 'billable');
    }

    public function assignedPayments()
    {
        return $this->belongsTo(LumpSumPayment::class, 'business_location_id', 'business_location_id');
    }

    public function penalties()
    {
        return $this->hasMany(LumpSumPenalties::class, 'return_id');
    }
}
