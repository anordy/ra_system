<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithheldCertificate extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'withholding_receipt_date' => 'datetime',
        'vfms_receipt_date' => 'datetime'
    ];

    public function return(){
        return $this->morphTo();
    }
}
