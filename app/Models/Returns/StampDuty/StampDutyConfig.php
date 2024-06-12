<?php

namespace App\Models\Returns\StampDuty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StampDutyConfig extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    const STAMP_DUTY_CODES = [
        'EXIMP',
        'LOCPUR',
        'IMPPUR',
    ];

    const STAMP_DUTY_SALES_CODES = [
        'SUP', // Optional: Add descriptions if needed
        'INST',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function headings()
    {
        return $this->hasMany(StampDutyConfigHead::class);
    }
}
