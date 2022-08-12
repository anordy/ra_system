<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingStatus extends Model
{
    use HasFactory;
    public const CN_GENERATING = 'control-number-generating';
    public const CN_GENERATED = 'control-number-generated';
    public const CN_GENERATION_FAILED = 'control-number-generating-failed';
    public const PAID_PARTIALLY = 'paid-partially';
    public const COMPLETE = 'complete';
}
