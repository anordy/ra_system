<?php

namespace App\Models\PropertyTax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CondominiumStorey extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'condominium_storeys';

    protected $guarded = [];

    public function condominium(): BelongsTo
    {
        return $this->belongsTo(Condominium::class, 'condominium_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(CondominiumUnit::class, 'condominium_storey_id');
    }
}
