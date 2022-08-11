<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxRegion extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function businesses(){
        return $this->hasMany(Business::class);
    }
}
