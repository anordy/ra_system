<?php

namespace App\Models\Debts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtPenalty extends Model
{
    use HasFactory;
    protected $table = 'debt_penalties';
    protected $guarded = [];
    
    public function debt(){
        return $this->belongsTo(Debt::class, 'debt_id');
    }
}
