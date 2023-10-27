<?php

namespace App\Models\PropertyTax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyPaymentReminder extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function payment(){
        return $this->belongsTo(PropertyPayment::class, 'property_payment_id');
    }
}
