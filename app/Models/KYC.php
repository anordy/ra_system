<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class KYC extends Model implements Auditable
{
    protected $table = 'kycs';

    protected $guarded = [];

    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    public function region(){
        return $this->belongsTo(Region::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function identification(){
        return $this->belongsTo(IDType::class, 'id_type');
    }
}
