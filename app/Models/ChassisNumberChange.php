<?php

namespace App\Models;

use App\Models\Tra\ChassisNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChassisNumberChange extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function chassis(){
        return $this->belongsTo(ChassisNumber::class, 'chassis_number_id');
    }

    public function particular(){
        return $this->belongsTo(MvrRegistrationParticularChange::class, 'particular_id');
    }

}
