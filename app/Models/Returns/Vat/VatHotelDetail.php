<?php

namespace App\Models\Returns\Vat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VatHotelDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
}
