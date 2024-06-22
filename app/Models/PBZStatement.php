<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PBZStatement extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pbz_statements';

    protected $guarded = [];

    protected $casts = [
        'credttm' => 'datetime',
        'stmdt' => 'datetime'
    ];

    public function pbzTransactions()
    {
        return $this->belongsToMany(PBZTransaction::class,  'pbz_transaction_statement', 'pbz_statement_id', 'pbz_transaction_id')
            ->withTimestamps();
    }

    public function pbzReversals()
    {
        return $this->belongsToMany(PBZReversal::class,  'pbz_reversal_statement', 'pbz_statement_id','pbz_reversal_id')
            ->withTimestamps();
    }
}
