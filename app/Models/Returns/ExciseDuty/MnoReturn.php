<?php

namespace App\Models\Returns\ExciseDuty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MnoReturn extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
}
