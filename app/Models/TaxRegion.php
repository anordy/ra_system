<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TaxRegion extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function businesses(){
        return $this->hasMany(Business::class);
    }

    public function departments()
    {
        return $this->belongsTo(TaxDepartment::class,'department_id');
    }
}
