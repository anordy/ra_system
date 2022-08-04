<?php

namespace App\Models\Returns\StampDuty;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StampDutyReturn extends Model
{
    use HasFactory, SoftDeletes;

    public const SUBMITTED = 'submitted';
    public const COMPLETE = 'complete';

    protected $guarded = [];
}
