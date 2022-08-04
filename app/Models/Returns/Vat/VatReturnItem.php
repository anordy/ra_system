<?php

namespace App\Models\Returns\Vat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatReturnItem extends Model
{
    use HasFactory;

    public function config()
    {
        return $this->belongsTo(VatReturnConfig::class,'vat_return_config_id','id');
    }
}
