<?php

namespace App\Models\Debts;

use App\Models\Returns\TaxReturn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DebtPenalty extends Model
{
    use HasFactory;
    protected $table = 'debt_penalties';
    protected $guarded = [];
    
    public function tax_return(){
        return $this->belongsTo(TaxReturn::class, 'tax_return_id');
    }
}
