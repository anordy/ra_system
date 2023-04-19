<?php

namespace App\Models\Returns\Vat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VatReturnItem extends Model
{
    use HasFactory, SoftDeletes;

    public function config()
    {
        return $this->belongsTo(VatReturnConfig::class,'config_id','id');
    }
}
