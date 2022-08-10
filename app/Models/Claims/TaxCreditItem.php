<?php

namespace App\Models\Claims;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxCreditItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}
