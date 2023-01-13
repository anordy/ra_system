<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class District extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $fillable = [
        'name',
        'region_id',
        'is_approved',
        'is_updated',
   ];

    public function wards(){
        return $this->hasMany(Ward::class);
    }

    public function region(){
        return $this->belongsTo(Region::class);
    }

    public function taxagent(){
        return $this->hasOne(TaxAgent::class);
    }

    public function landLeases(){
        $this->hasMany(LandLease::class,'district_id');
    }

    public function scopeApproved($query){
        return $query->where('is_approved', true);
    }
}
