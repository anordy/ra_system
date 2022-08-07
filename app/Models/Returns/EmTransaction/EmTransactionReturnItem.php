<?php

namespace App\Models\Returns\EmTransaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmTransactionReturnItem extends Model
{
    use HasFactory;

    public function emTransactionConfig() {
        return $this->belongsTo(EmTransactionConfig::class, 'config_id');
    }

    public function emTransactionReturn() {
        return $this->belongsTo(EmTransactionReturn::class, 'bfo_return_id');
    }
}
