<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaIncedent extends Model
{
    CONST PENDING = 'PENDING';
    CONST APPROVED = 'APPROVED';
    CONST REJECTED = 'REJECTED';
    CONST CORRECTION = 'CORRECTION';
    
    use HasFactory;

    protected $table = 'ra_incedents';
}
