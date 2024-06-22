<?php

namespace App\Models\Installment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentList extends Model
{
    use HasFactory;

    public function installments(){
        return $this->belongsTo(Installment::class);
    }
}
