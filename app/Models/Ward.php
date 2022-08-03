<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Ward extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $fillable = ['district_id', 'name'];

    public function district() {
        return $this->belongsTo(District::class, 'district_id');
    }
 
    public function landLeases()
    {
        $this->hasMany(LandLease::class,'ward_id');
    }
}
