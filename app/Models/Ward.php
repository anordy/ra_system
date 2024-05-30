<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Ward extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = ['district_id', 'name', 'is_approved', 'is_updated'];

    public function district() {
        return $this->belongsTo(District::class, 'district_id');
    }
    
    public function landLeases()
    {
        $this->hasMany(LandLease::class,'ward_id');
    }

    public function activeStreets(){
        return $this->hasMany(Street::class)->where('is_approved', DualControl::APPROVE)->select('id', 'name');
    }

    public function streets(){
        return $this->hasMany(Street::class);
    }

    public function scopeApproved($query){
        return $query->where('is_approved', true);
    }

    public function vfms_ward(){
        return $this->hasOne(VfmsWard::class, 'ward_id');
    }

    public function vfmsLocalityData(){
        $district = District::select('id', 'region_id', 'name')->where('id', $this->district_id)->first();
        $region = Region::select('name')->first();
        return [
            'district_name' => $district->name,
            'locality_name' => $this->name,
            'region_name' => $region->name
        ];
    }
}
