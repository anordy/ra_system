<?php

namespace App\Models\Returns\Port;

use App\Models\TaxType;
use App\Models\Business;
use App\Models\Taxpayer;
use App\Models\FinancialYear;
use App\Models\FinancialMonth;
use App\Models\BusinessLocation;
use Illuminate\Database\Eloquent\Model;
use App\Models\Returns\Port\PortReturnItem;
use App\Models\Returns\Port\PortReturnPenalty;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function penalties(){
        return $this->hasMany(PortReturnPenalty::class, 'return_id');
    }

}
