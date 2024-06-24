<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sequence extends Model
{
    use HasFactory, SoftDeletes;

    const TAX_CLEARANCE = 'taxClearance';
    const TAX_CLEARANCE_YEAR = 'taxClearanceYear';

    protected $guarded = [];
}
