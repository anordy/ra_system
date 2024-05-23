<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MvrPlateNumberType extends Model
{
    use HasFactory, SoftDeletes;

    const SPECIAL_NAME = 'special-name';
    const NON_SPECIAL = 'non-special';
    const PERSONALIZED = 'personalized';
}
