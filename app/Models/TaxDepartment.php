<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxDepartment extends Model
{
    use HasFactory;

    public  function taxRegion()
    {
        return $this->hasMany(TaxRegion::class, 'department_id');
    }
}
