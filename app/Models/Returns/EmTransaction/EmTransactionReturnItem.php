<?php

namespace App\Models\Returns\EmTransaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmTransactionReturnItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = 'em_transaction_return_items';

    public function config() {
        return $this->belongsTo(EmTransactionConfig::class, 'config_id');
    }

    public function return() {
        return $this->belongsTo(EmTransactionReturn::class, 'return_id');
    }
}
