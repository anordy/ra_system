<?php

namespace App\Models\Ntr;

use App\Models\Returns\Vat\SubVat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NtrBusinessTaxType extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function subvat() {
        return $this->belongsTo(SubVat::class, 'sub_vat_id');
    }
}
