<?php

namespace App\Models;

use App\Models\PublicService\PublicServiceMotor;
use App\Models\TaxpayerLedger\TaxpayerLedger;
use App\Models\Tra\TancisChassisNumber;
use App\Models\Tra\Tin;
use App\Traits\WorkflowTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MvrRegistration extends Model
{
    use HasFactory, SoftDeletes, WorkflowTrait;

    protected $guarded = [];

    public function chassis(){
        return $this->belongsTo(TancisChassisNumber::class, 'chassis_number_id');
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

    public function publicService(){
        return $this->hasOne(PublicServiceMotor::class, 'mvr_registration_id');
    }

    public function temporaryTransports(){
        return $this->hasMany(MvrTemporaryTransport::class);
    }

    public function ledger()
    {
        return $this->morphOne(TaxpayerLedger::class, 'source');
    }

    public function attachments() {
        return $this->hasMany(MvrRegistrationAttachment::class, 'mvr_registration_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    public function location() {
        return "{$this->region->name} {$this->district->name} {$this->ward->name} {$this->street->name}";
    }
}
