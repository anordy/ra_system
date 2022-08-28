<?php

namespace App\Models\Debts;

use App\Models\Debts\Debt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DebtPenalty extends Model
{
    use HasFactory;
    protected $table = 'debt_penalties';
    protected $guarded = [];
    
    public function debt(){
        return $this->belongsTo(Debt::class, 'debt_id');
    }
}
