<?php

namespace App\Models\PropertyTax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CondominiumUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'condominium_units';

    protected $guarded = [];

    public function storey(): BelongsTo
    {
        return $this->belongsTo(CondominiumStorey::class);
    }

    public function condominium(): BelongsTo
    {
        return $this->belongsTo(Condominium::class);
    }
}
