<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandLeaseDebt extends Model
{
    use HasFactory;
    protected $table = 'land_lease_debts';
    protected $guarded = [];

    public function LeasePayment(){
        return $this->belongsTo(LeasePayment::class, 'lease_payment_id');
    }
}
