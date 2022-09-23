<?php

namespace App\Models\Installment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstallmentRequestFile extends Model
{
    use HasFactory, SoftDeletes;

    public function installmentRequest(){
        return $this->belongsTo(InstallmentRequest::class);
    }
}
