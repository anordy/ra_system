<?php

namespace App\Models\Debts;

use App\Models\Returns\TaxReturn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DebtPenalty extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'debt_penalties';
    protected $guarded = [];
    
    public function tax_return(){
        return $this->belongsTo(TaxReturn::class, 'tax_return_id');
    }
}
