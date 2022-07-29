<?php

namespace App\Models\Returns\StampDuty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StampDutyService extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeActive($query){
        return $query->where('is_active', true);
    }
}
