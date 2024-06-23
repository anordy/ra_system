<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiskIndicator extends Model
{
    use HasFactory;

    protected $table = 'returns_risk_indicators';

    protected $fillable = [
        'risk_indicator',
        'risk_level',
        'slug',
        
    ];
}
