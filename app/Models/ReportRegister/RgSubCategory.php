<?php

namespace App\Models\ReportRegister;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RgSubCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function category() {
        return $this->belongsTo(RgCategory::class, 'rg_category_id');
    }

    public function notifiables() {
        return $this->hasMany(RgSubCategoryNotifiable::class, 'rg_sub_category_id');
    }

}
