<?php

namespace App\Models\PublicService;

use App\Models\Business;
use App\Models\MvrRegistration;
use App\Models\Taxpayer;
use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicServiceReturn extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }

    public function business(){
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function motor(){
        return $this->belongsTo(PublicServiceMotor::class, 'public_service_motor_id');
    }

    public function bills(){
        return $this->morphMany(ZmBill::class, 'billable');
    }

    public function bill(){
        return $this->morphOne(ZmBill::class, 'billable');
    }

    public function latestBill(){
        return $this->morphOne(ZmBill::class, 'billable')->latest();
    }
}
