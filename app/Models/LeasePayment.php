<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeasePayment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'lease_payments';
    protected $guarded = [];

    public function landlease(){
        return $this->belongsTo(LandLease::class);
    }
}
