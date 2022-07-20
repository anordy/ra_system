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

    protected $casts = [
        'date_of_commencing' => 'datetime'
    ];

    public function taxpayer(){
        return $this->belongsTo(Taxpayer::class);
    }

    public function partners(){
        return $this->hasMany(BusinessPartner::class);
    }

    public function category(){
        return $this->belongsTo(BusinessCategory::class, 'business_category_id');
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

    public function headquarter(){
        return $this->hasOne(BusinessLocation::class)->where('is_headquarter', true);
    }

    public function branches(){
        return $this->hasMany(BusinessLocation::class)->where('is_headquarter', false);
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

    public function taxTypeChanges(){
        return $this->hasMany(BusinessTaxTypeChange::class);
    }

    public function openBusiness(){
        return $this->hasOne(TemporaryBusinessClosure::class)->latest()->open();
    }

    public function businessStatus(){
        return $this->hasOne(BusinessStatus::class);
    }

    public function businessUpdate(){
        return $this->hasOne(BusinessUpdate::class);
    }
    // Files Relation
    public function files(){
        return $this->hasMany(BusinessFile::class);
    }

    // Scopes
    public function scopeApproved($query){
        $query->where('status', BusinessStatus::APPROVED);
    }

    public function scopeClosed($query){
        $query->where('status', BusinessStatus::TEMP_CLOSED);
    }
}
