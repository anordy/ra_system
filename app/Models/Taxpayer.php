<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxpayer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_no',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'mobile',
        'alt_mobile',
        'location',
        'physical_address',
        'street',
        'is_citizen',
        'id_type',
        'id_number',
        'work_permit',
        'residence_permit',
        'country_id',
        'biometric_verified_at',
        'password'
    ];

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function identification(){
        return $this->belongsTo(IDType::class, 'id_type');
    }
    
    public function otp()
    {
        return $this->morphOne(UserOtp::class, 'user');
    }

    public function fullname(){
        return $this->first_name. ' '. $this->last_name;
    }
}
