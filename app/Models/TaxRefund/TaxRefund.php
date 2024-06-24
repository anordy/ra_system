<?php

namespace App\Models\TaxRefund;

use App\Models\BusinessLocation;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxRefund extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function items() {
        return $this->hasMany(TaxRefundItem::class, 'refund_id');
    }

    public function bill()
    {
        return $this->morphOne(ZmBill::class, 'billable');
    }

    public function latestBill()
    {
        return $this->morphOne(ZmBill::class, 'billable')->latest();
    }

    public function businessLocation(){
        return $this->belongsTo(BusinessLocation::class, 'business_location_id');
    }
}
