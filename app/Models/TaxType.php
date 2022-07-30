<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class TaxType extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    public const STAMP_DUTY = 'stamp-duty';
    public const LUMPSUM_PAYMENT = 'lumpsum-payment';

    protected $fillable = [
        'name'
    ];
}
