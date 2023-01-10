<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class KYC extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $table = 'kycs';

    protected $guarded = [];


    public function region(){
        return $this->belongsTo(Region::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function identification(){
        return $this->belongsTo(IDType::class, 'id_type');
    }

    public function fullname(){
        return $this->first_name.' '. $this->middle_name .' '. $this->last_name;
    }
}
