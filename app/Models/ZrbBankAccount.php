<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ZrbBankAccount extends Model implements Auditable
{
    use HasFactory, SoftDeletes, \OwenIt\Auditing\Auditable;

    protected $guarded = [];

    public function bank(){
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function currency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
