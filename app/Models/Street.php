<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Street extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function ward(){
        return $this->belongsTo(Ward::class);
    }

    public function taxpayer(){
        return $this->hasMany(Taxpayer::class);
    }

    public function kyc(){
        return $this->hasMany(KYC::class);
    }
}
