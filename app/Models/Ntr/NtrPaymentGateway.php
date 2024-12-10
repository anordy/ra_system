<?php

namespace App\Models\Ntr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NtrPaymentGateway extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

}
