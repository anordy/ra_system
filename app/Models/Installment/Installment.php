<?php

namespace App\Models\Installment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Installment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function items(){
        return $this->hasMany(InstallmentItem::class);
    }

    public function request(){
        return $this->belongsTo(InstallmentRequest::class, 'installment_request_id');
    }
}
