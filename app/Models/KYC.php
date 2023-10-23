<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class KYC extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable, SoftDeletes;

    protected $table = 'kycs';

    protected $guarded = [];

    protected $casts = [
        'tin_verified_at' => 'datetime',
        'zanid_verified_at' => 'datetime'
    ];

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function region(){
        return $this->belongsTo(Region::class);
    }

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function ward(){
        return $this->belongsTo(Ward::class);
    }

    public function street(){
        return $this->belongsTo(Street::class);
    }

    public function identification(){
        return $this->belongsTo(IDType::class, 'id_type');
    }

    public function fullname(){
        return $this->first_name.' '. $this->middle_name .' '. $this->last_name;
    }

    public function amendments(){
        return $this->hasMany(KycAmendmentRequest::class, 'kyc_id');
    }

    public function checkPendingAmendment(){
        foreach ($this->amendments()->get() as $amendment){
            if ($amendment['status'] == KycAmendmentRequest::PENDING){
                return true;
            }
        }
        return false;
    }
}
