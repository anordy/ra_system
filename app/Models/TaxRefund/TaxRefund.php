<?php

namespace App\Models\TaxRefund;

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
}
