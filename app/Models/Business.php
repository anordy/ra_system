<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date_of_commencing' => 'datetime'
    ];

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

    public function consultant(){
        return $this->hasOne(BusinessConsultant::class);
    }

    public function consultantRequest(){
        return $this->hasOne(BusinessConsultantRequest::class);
    }

    public function temporaryBusinessClosures(){
        return $this->hasMany(TemporaryBusinessClosure::class);
    }

    public function openBusiness(){
        return $this->hasOne(TemporaryBusinessClosure::class)->latest()->open();
    }
}
