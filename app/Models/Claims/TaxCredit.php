<?php

namespace App\Models\Claims;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxCredit extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function items(){
        return $this->hasMany(TaxCreditItem::class, 'credit_id');
    }
}
