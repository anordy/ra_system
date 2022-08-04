<?php

namespace App\Models\Returns\Vat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VatReturnConfig extends Model
{
    use HasFactory;
    protected $table = 'vat_return_configs';
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(VatReturnItem::class, 'vat_return_config_id', 'id');
    }
}
