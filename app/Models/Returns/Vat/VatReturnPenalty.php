<?php

namespace App\Models\Returns\Vat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatReturnPenalty extends Model
{
    use HasFactory;
    protected $table = 'vat_return_penalties';
    protected $guarded = [];
}
