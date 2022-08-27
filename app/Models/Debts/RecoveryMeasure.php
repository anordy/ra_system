<?php

namespace App\Models\Debts;

use App\Models\Debts\Debt;
use Illuminate\Database\Eloquent\Model;
use App\Models\Debts\RecoveryMeasureCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecoveryMeasure extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function debt() {
        return $this->belongsTo(Debt::class, 'debt_id');
    }

    public function category() {
        return $this->belongsTo(RecoveryMeasureCategory::class, 'recovery_measure_id');
    }

}
