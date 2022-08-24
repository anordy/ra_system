<?php

namespace App\Models\Installment;

use App\Models\ZmBill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstallmentItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function bill(){
        return $this->morphOne(ZmBill::class, 'billable');
    }

    public function installment(){
        return $this->belongsTo(Installment::class);
    }
}
