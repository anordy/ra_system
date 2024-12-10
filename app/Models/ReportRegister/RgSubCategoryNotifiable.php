<?php

namespace App\Models\ReportRegister;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class RgSubCategoryNotifiable extends Model
{
    use HasFactory;

    protected $table = 'rg_sub_category_notifiables';

    protected $guarded = [];

    public function category() {
        return $this->belongsTo(RgCategory::class, 'rg_sub_category_id');
    }

    public function role() {
        return $this->belongsTo(Role::class, 'role_id');
    }

}
