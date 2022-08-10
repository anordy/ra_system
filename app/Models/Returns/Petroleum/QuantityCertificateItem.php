<?php

namespace App\Models\Returns\Petroleum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuantityCertificateItem extends Model
{
    use HasFactory;

    protected $guarded =  [];

    public function config(){
        return $this->belongsTo(PetroleumConfig::class);
    }
}
