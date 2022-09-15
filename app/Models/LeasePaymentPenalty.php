<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeasePaymentPenalty extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'lease_payment_penalties';
    protected $guarded = [];

    public function LeasePayment(){
        return $this->belongsTo(LeasePayment::class, 'lease_payment_id');
    }
}
