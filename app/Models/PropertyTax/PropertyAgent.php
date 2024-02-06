<?php

namespace App\Models\PropertyTax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyAgent extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
