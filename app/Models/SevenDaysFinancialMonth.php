<?php

namespace App\Models;

use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SevenDaysFinancialMonth extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;
    protected $guarded = [];


    public function year(){
        return $this->belongsTo(FinancialYear::class, 'financial_year_id');
    }

    public function petroleumReturns()
    {
        return $this->hasMany(PetroleumReturn::class, 'financial_month_id');
    }

    public function mmTransferReturns()
    {
        return $this->hasMany(MmTransferReturn::class, 'financial_month_id');
    }

    public function emTransactionReturns()
    {
        return $this->hasMany(EmTransactionReturn::class, 'financial_month_id');
    }
}
