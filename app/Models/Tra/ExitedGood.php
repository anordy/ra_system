<?php

namespace App\Models\Tra;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitedGood extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const IM4 = 'IM4';
    public const IM9 = 'IM9';
}
