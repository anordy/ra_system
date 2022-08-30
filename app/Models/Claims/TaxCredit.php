<?php

namespace App\Models\Claims;

use App\Enum\TaxClaimStatus;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\FinancialMonth;
use App\Models\TaxType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxCredit extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function location(){
        return $this->belongsTo(BusinessLocation::class, 'location_id');
    }

    public function financialMonth(){
        return $this->belongsTo(FinancialMonth::class);
    }

    public function taxType(){
        return $this->belongsTo(TaxType::class);
    }

    public function scopeApproved($query){
        return $query->where('status', TaxClaimStatus::APPROVED);
    }

    public function items(){
        return $this->hasMany(TaxCreditItem::class, 'credit_id');
    }

    /**
     * Check if there are remaining amount to be used in CBF
     * @param $currency
     * @return bool
     */
    public function hasCredit($currency): bool
    {
        if ($this->payment_method == 'cash' || $this->currency != $currency){
            return false;
        }

        if ($this->items()->sum('amount') < $this->amount){
            return true;
        }

        return false;
    }

    /**
     * Return the total amount of spent credit (CBF)
     * @return array
     */
    public function getSpentCreditAttribute()
    {
        return $this->items()->sum('amount');
    }

}
