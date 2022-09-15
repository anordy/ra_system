<?php

namespace App\Models\Debts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Debts\RecoveryMeasureCategory;
use App\Models\Returns\TaxReturn;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecoveryMeasure extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function debt() {
        return $this->belongsTo(TaxReturn::class, 'tax_return_id');
    }

    public function category() {
        return $this->belongsTo(RecoveryMeasureCategory::class, 'recovery_measure_id');
    }

}
