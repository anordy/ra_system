<?php

namespace App\Models\Returns\StampDuty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StampDutyConfig extends Model
{
    use HasFactory, SoftDeletes;

    public function scopeActive($query){
        return $query->where('is_active', true);
    }
}
