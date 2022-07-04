<?php

namespace App\Models;

use App\Traits\WorkflowTrait;
use App\Models\BusinessStatus;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Business extends Model implements Auditable
{
    use HasFactory, WorkflowTrait;
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class);
    }

    public function partners(){
        return $this->hasMany(BusinessPartner::class);
    }

    public function category(){
        return $this->belongsTo(BusinessCategory::class);
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }

    public function taxTypes(){
        return $this->belongsToMany(TaxType::class);
    }

    public function activityType(){
        return $this->belongsTo(BusinessActivity::class, 'business_activities_type_id');
    }

    public function location(){
        return $this->hasOne(BusinessLocation::class);
    }

    public function bank(){
        return $this->hasOne(BusinessBank::class);
    }

    public function consultants(){
        return $this->hasMany(BusinessConsultant::class);
    }

    public function responsiblePerson(){
        return $this->belongsTo(Taxpayer::class, 'responsible_person_id');
    }

    public function temporaryBusinessClosures(){
        return $this->hasMany(TemporaryBusinessClosure::class);
    }

    public function openBusiness(){
        return $this->hasOne(TemporaryBusinessClosure::class)->latest()->open();
    }

  
    public function businessStatus(){
        return $this->hasOne(BusinessStatus::class);
    }
}
