<?php

namespace App\Models\Tra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TancisChassisNumber extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = 'tancis_chassis_numbers';
}
