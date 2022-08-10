<?php

namespace App\Models\Claims;

use App\Models\Business;
use App\Models\FinancialMonth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxClaim extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function financialMonth(){
        return $this->belongsTo(FinancialMonth::class);
    }

    public function oldReturn(){
        return $this->morphTo();
    }

    public function newReturn(){
        return $this->morphTo();
    }
}
