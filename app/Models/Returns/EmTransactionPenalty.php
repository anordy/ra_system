<?php

namespace App\Models\Returns;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmTransactionPenalty extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'em_transaction_penalties';

    public function emTransactionReturn(){
        return $this->belongsTo(EmTransactionReturn::class);
    }
}
