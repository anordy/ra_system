<?php

namespace App\Models\PublicService;

use App\Models\Business;
use App\Models\MvrRegistration;
use App\Models\Taxpayer;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicServiceMotor extends Model
{
    use HasFactory, WorkflowTrait;

    protected $guarded = [];

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }

    public function business(){
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function mvr(){
        return $this->belongsTo(MvrRegistration::class, 'mvr_registration_id');
    }

    public function payment(){
        return $this->hasOne(PublicServicePayment::class, 'public_service_motor_id');
    }

}
