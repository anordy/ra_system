<?php

namespace App\Models\Returns\StampDuty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StampDutyReturnItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function config() {
        return $this->belongsTo(StampDutyConfig::class, 'config_id');
    }
}
