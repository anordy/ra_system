<?php

namespace App\Models\PublicService;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicServicePayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function publicServiceMotor(){
        return $this->belongsTo(PublicServiceMotor::class, 'public_service_motor_id');
    }

    public function paymentCategory(){
        return $this->belongsTo(PublicServicePaymentCategory::class, 'public_service_payment_category_id');
    }
}
