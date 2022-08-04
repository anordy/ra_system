<?php

namespace App\Models\Returns\Port;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Returns\Port\PortReturnItem;
use App\Models\Taxpayer;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortReturn extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function configReturns()
    {
        return $this->hasMany(PortReturnItem::class, 'return_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function branch()
    {
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class, 'filled_id');
    }

    public function taxtype()
    {
        return $this->belongsTo(TaxType::class, 'tax_type_id');
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function financialMonth()
    {
        return $this->belongsTo(FinancialMonth::class, 'financial_month_id');
    }

}
