<?php

namespace App\Models\ReportRegister;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RgAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function register() {
        return $this->belongsTo(RgRegister::class, 'rg_register_id');
    }
}
