<?php

namespace App\Models\Debts;

use App\Models\Debts\Debt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecoveryMeasureCategory extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function debt(){
        return $this->belongsTo(Debt::class, 'debt_id');
    }


}
