<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BusinessBank extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_id',
        'bank_id',
        'account_type_id',
        'currency_id',
        'acc_no',
        'branch',
    ];

    public function business(){
        return $this->belongsTo(Business::class);
    }

    public function currency(){
        return $this->belongsTo(Currency::class);
    }

    public function bank(){
        return $this->belongsTo(Bank::class);
    }

    public function accountType(){
        return $this->belongsTo(AccountType::class);
    }
}
