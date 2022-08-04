<?php

namespace App\Models\Returns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnStatus extends Model
{
    public const SUBMITTED = 'submitted';
    public const CN_GENERATING = 'control-number-generating';
    public const CN_GENERATED = 'control-number-generated';
    public const CN_GENERATION_FAILED = 'control-number-generating-failed';
    public const COMPLETE = 'complete';
}
