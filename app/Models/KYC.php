<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KYC extends Model
{
    protected $table = 'kycs';

    use HasFactory, SoftDeletes;
}
