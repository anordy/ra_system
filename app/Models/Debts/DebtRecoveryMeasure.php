<?php

namespace App\Models\Debts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Debts\RecoveryMeasureCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DebtRecoveryMeasure extends Model
{
    use HasFactory, SoftDeletes;

    public function category() {
        return $this->belongsTo(RecoveryMeasureCategory::class, 'recovery_measure_category_id');
    }

    public function recoveryMeasure() {
        return $this->belongsTo(RecoveryMeasureCategory::class, 'recovery_measure_id');
    }
}
