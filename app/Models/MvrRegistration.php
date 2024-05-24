<?php

namespace App\Models;

use App\Models\Tra\ChassisNumber;
use App\Models\Tra\Tin;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class MvrRegistration extends Model
{
    use HasFactory, SoftDeletes, WorkflowTrait;

    protected $guarded = [];

    public function chassis(){
        return $this->belongsTo(ChassisNumber::class, 'chassis_number_id');
    }

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class, 'taxpayer_id');
    }

    public function agent(){
        return $this->hasOne(MvrAgent::class, 'taxpayer_id', 'taxpayer_id');
    }

    public function regtype(){
        return $this->belongsTo(MvrRegistrationType::class, 'mvr_registration_type_id');
    }

    public function platesize(){
        return $this->belongsTo(MvrPlateSize::class, 'mvr_plate_size_id');
    }

    public function plate_type(){
        return $this->belongsTo(MvrPlateNumberType::class, 'mvr_plate_number_type_id');
    }

    public function class(){
        return $this->belongsTo(MvrClass::class, 'mvr_class_id');
    }

    public function tin(){
        return $this->hasOne(Tin::class, 'tin', 'registrant_tin');
    }

    public function inspection(){
        return $this->hasOne(MvrInspectionReport::class, 'mvr_registration_id');
    }

    public function cor(){
        return $this->hasOne(Cor::class);
    }

    public function latestBill()
    {
        return $this->morphOne(ZmBill::class, 'billable')->latest();
    }

}
