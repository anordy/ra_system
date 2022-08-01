<?php

namespace App\Models\returns\ExciseDuty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MnoReturnItem extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'mno_return_items';
}
