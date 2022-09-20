<?php

namespace App\Models\Debts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Debts\RecoveryMeasureCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DebtRecoveryMeasure extends Model
{
    use HasFactory;

    public function category() {
        return $this->belongsTo(RecoveryMeasureCategory::class, 'recovery_measure_category_id');
    }

    public function recoveryMeasure() {
        return $this->belongsTo(RecoveryMeasureCategory::class, 'recovery_measure_id');
    }
}
