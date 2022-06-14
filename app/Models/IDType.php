<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IDType extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'id_types';

    protected $fillable = [
        'name',
        'description'
    ];
}
