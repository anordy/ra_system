<?php

namespace App\Models\PropertyTax;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function property(){
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function storey(){
        return $this->belongsTo(PropertyStorey::class, 'property_id');
    }

}
