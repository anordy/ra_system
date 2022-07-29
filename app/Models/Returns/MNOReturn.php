<?php

namespace App\Models\Returns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MNOReturn extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];
}
