<?php

namespace App\Models\Returns\ExciseDuty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MnoConfig extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'mno_configs';
}
