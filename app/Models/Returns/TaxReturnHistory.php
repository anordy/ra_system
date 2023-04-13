<?php

namespace App\Models\Returns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxReturnHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'tax_return_id',
        'return_info',
        'return_items',
        'penalties',
        'version',
    ];
}
