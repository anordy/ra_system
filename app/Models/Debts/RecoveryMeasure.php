<?php

namespace App\Models\Debts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
