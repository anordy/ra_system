<?php

namespace App\Models\Returns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MmTransferReturn extends Model
{
    use HasFactory;

    public function bill(){
        return $this->morphOne(ZmBill::class, 'billable');
    }
}
