<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Region extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable, SoftDeletes;

    public const UNGUJA = 'unguja';
    public const PEMBA = 'pemba';

    protected $fillable = [
        'code',
        'name',
        'location',
        'registration_count',
        'is_approved',
        'is_updated',
   ];

    public function taxagent()
    {
        return $this->hasOne(TaxAgent::class);
    }

    public function landLeases()
    {
        $this->hasMany(LandLease::class,'region_id');
    }

    public function activeDistricts(){
        return $this->hasMany(District::class)->where('is_approved', DualControl::APPROVE)->select('id', 'name');
    }

    public function disricts(){
        return $this->hasMany(District::class);
    }

    public function scopeApproved($query){
        return $query->where('is_approved', true);
    }
}
