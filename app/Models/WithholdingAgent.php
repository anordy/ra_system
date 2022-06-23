<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WithholdingAgent extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class, 'officer_id');
    }

    public function taxpayer() {
        return $this->belongsTo(Taxpayer::class, 'responsible_person_id');
    }

    public function ward() {
        return $this->belongsTo(Ward::class, 'ward_id');
    }

    public function district() {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function region() {
        return $this->belongsTo(Region::class, 'region_id');
    }

}
