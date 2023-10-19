<?php

namespace App\Models\PropertyTax;

use App\Models\IDType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyOwner extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];


    public function property(){
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function idType(){
        return $this->belongsTo(IDType::class, 'id_type');
    }

}
