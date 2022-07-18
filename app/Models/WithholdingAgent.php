<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WithholdingAgent extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function ward() {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function district() {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function region() {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function latestResponsiblePerson() {
        return $this->hasOne(WaResponsiblePerson::class)->latest();
    }

    public function responsiblePersons() {
        return $this->hasMany(WaResponsiblePerson::class);
    }

}
